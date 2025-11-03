<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PromoModel;
use App\Models\ProductModel;
use App\Models\StoreModel;
use CodeIgniter\I18n\Time;

class Promos extends BaseController
{
  protected $promoModel;
  protected $storeModel;
  protected $productModel;
  protected $helpers = ['url', 'menu'];
  public function __construct()
  {
    $this->promoModel = new PromoModel();
    $this->storeModel = new StoreModel();
    $this->productModel = new ProductModel();
  }

  public function index()
  {
    $promos = $this->promoModel->withJoins()->orderBy('promos.id', 'DESC')->paginate(10);
    $data = [
      'title' => 'Promo List',
      'promos' => $promos,
    ];
    return view('promos/index', $data);
  }

  public function create()
  {
    $data = [
      'title' => 'Create Promo Form',
      'validation' => \Config\Services::validation(),
      'promos' => $this->promoModel->findAll(),
      'stores' => $this->storeModel->orderBy('store_name','ASC')->findAll(),
      'products' => $this->productModel->orderBy('name','ASC')->findAll(),
    ];

    return view('promos/create', $data);
  }

  public function store()
  {
    $rules = [
      'store_id'       => 'required',
      'name'            => 'required|min_length[3]',
      'promo_code'      => 'required|min_length[2]|max_length[20]|is_unique[promos.promo_code]',
      'type'            => 'required|in_list[percent,fixed]',
      'value'           => 'required|decimal',
      'start_datetime'  => 'required',
      'end_datetime'    => 'required',
    ];

    if (! $this->validate($rules)) {
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $post = $this->request->getPost();
    $data = [
      'store_id' => (int) $post['store_id'],
      'promo_code' => $post['promo_code'],
      'name' => $post['name'],
      'type' => $post['type'],
      'value' => $post['value'],
      'start_datetime' => $post['start_datetime'],
      'end_datetime' => $post['end_datetime'],
    ];

    $db = db_connect();
    $db->transBegin();
    try {
      $promoId = $this->promoModel->insert($data, true);
      $productIds = (array) ($post['product_id'] ?? ($post['product_ids'] ?? []));
      $productIds = array_values(array_unique(array_map('intval', $productIds)));
      $productIds = array_filter($productIds, fn($v) => $v > 0);
      if (!empty($productIds)) {
        $now  = Time::now();
        // Restore jika sebelumnya soft-deleted
        $db->table('promoitems')
          ->where('promo_id', $promoId)
          ->whereIn('product_id', $productIds)
          ->where('deleted_at !=', null)
          ->update(['deleted_at' => null, 'updated_at' => $now]);

        // Insert yang benar-benar belum ada
        $existing = $db->table('promoitems')->select('product_id')
          ->where('promo_id', $promoId)->whereIn('product_id', $productIds)
          ->get()->getResultArray();
        $existingIds = array_map('intval', array_column($existing, 'product_id'));
        $toInsert = array_values(array_diff($productIds, $existingIds));
        if (!empty($toInsert)) {
          $rows = [];
          foreach ($toInsert as $pid) {
            $rows[] = [
              'promo_id' => $promoId,
              'product_id' => (int) $pid,
              'created_at' => $now,
              'updated_at' => $now,
            ];
          }
          $db->table('promoitems')->insertBatch($rows);
        }
      }
      $db->transCommit();
    } catch (\Throwable $e) {
      $db->transRollback();
      return redirect()->back()->withInput()->with('errors', [$e->getMessage()]);
    }

    return redirect()->to('/promos')->with('success', 'Promo added successfully');
  }

  public function edit($id)
  {
    $promo = $this->promoModel->where('id', $id)->first();

    if (! $promo) {
      return redirect()->to('/promos')->with('error', 'Promo not found.');
    }

    $data = [
      'title'      => 'Edit Promo',
      'promo'      => $promo,
      'promos'     => $this->promoModel->findAll(),
      'stores'     => $this->storeModel->orderBy('store_name','ASC')->findAll(),
      'products'   => $this->productModel->orderBy('name','ASC')->findAll(),
      'selected_product_ids' => array_column(
        db_connect()->table('promoitems')
          ->select('product_id')
          ->where('promo_id', $id)
          ->where('deleted_at', null)
          ->get()->getResultArray(),
        'product_id'
      ),
      'validation' => \Config\Services::validation(),
    ];

    return view('promos/edit', $data);
  }

  public function update($id)
  {
    $promo = $this->promoModel->find($id);
    if (! $promo) {
      return redirect()->to('/promos')->with('error', 'Promo not found.');
    }

    $uniqueRule = 'is_unique[promos.promo_code,id,' . $id . ']';
    $rules = [
      'store_id'       => 'required',
      'promo_code'      => 'required|min_length[2]|max_length[20]|' . $uniqueRule,
      'name'            => 'required|min_length[3]',
      'type'            => 'required|in_list[percent,fixed]',
      'value'           => 'required|decimal',
      'start_datetime'  => 'required',
      'end_datetime'    => 'required',
    ];

    if (! $this->validate($rules)) {
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $data = [
      'id'             => $id,
      'store_id'       => $this->request->getPost('store_id'),
      'promo_code'     => $this->request->getPost('promo_code'),
      'type'           => $this->request->getPost('type'),
      'name'           => $this->request->getPost('name'),
      'value'          => $this->request->getPost('value'),
      'start_datetime' => $this->request->getPost('start_datetime'),
      'end_datetime'   => $this->request->getPost('end_datetime'),
    ];

    $db = db_connect();
    $db->transBegin();
    try {
      $this->promoModel->save($data);
      // Ambil input produk dari rows (product_id[]) atau fallback product_ids[]
      $postedIds = (array) $this->request->getPost('product_id');
      if (empty($postedIds)) { $postedIds = (array) $this->request->getPost('product_ids'); }
      $newProductIds = array_values(array_unique(array_map('intval', $postedIds)));
      $existing = $db->table('promoitems')->select('product_id')->where('promo_id', $id)->where('deleted_at', null)->get()->getResultArray();
      $existingIds = array_map('intval', array_column($existing, 'product_id'));

      $toAdd = array_values(array_diff($newProductIds, $existingIds));
      $toRemove = array_values(array_diff($existingIds, $newProductIds));

      if (!empty($toRemove)) {
        // Soft delete yang dihapus di UI
        $now = \CodeIgniter\I18n\Time::now();
        $db->table('promoitems')->where('promo_id', $id)->whereIn('product_id', $toRemove)
          ->update(['deleted_at' => $now, 'updated_at' => $now]);
      }
      if (!empty($toAdd)) {
        $now = \CodeIgniter\I18n\Time::now();
        // Restore yang sebelumnya soft-deleted
        $db->table('promoitems')->where('promo_id', $id)->whereIn('product_id', $toAdd)->where('deleted_at !=', null)
          ->update(['deleted_at' => null, 'updated_at' => $now]);
        // Insert yang benar-benar baru
        $exists = $db->table('promoitems')->select('product_id')->where('promo_id', $id)->whereIn('product_id', $toAdd)->get()->getResultArray();
        $existsIds = array_map('intval', array_column($exists, 'product_id'));
        $insertIds = array_values(array_diff($toAdd, $existsIds));
        if (!empty($insertIds)) {
          $rows = [];
          foreach ($insertIds as $pid) {
            $rows[] = ['promo_id' => $id, 'product_id' => $pid, 'created_at' => $now, 'updated_at' => $now];
          }
          $db->table('promoitems')->insertBatch($rows);
        }
      }
      $db->transCommit();
    } catch (\Throwable $e) {
      $db->transRollback();
      return redirect()->back()->withInput()->with('errors', [$e->getMessage()]);
    }

    return redirect()->to('/promos')->with('success', 'Promo updated successfully.');
  }

  public function delete($id)
  {
    $promo = $this->promoModel->find($id);

    if (! $promo) {
      return redirect()->to('/promos')->with('error', 'Promo not found.');
    }

    $promoName = $promo['name'];

    $this->promoModel->delete($id);
    session()->setFlashdata('message', "Promo deleted successfully");
    return redirect()->to('/promos')->with('success', 'Promo ' . $promoName . ' successfully deleted!');
  }

  // Return active promos for a given product and store (JSON)
  public function eligible()
  {
    $productId = (int) ($this->request->getGet('product_id') ?? 0);
    $storeId   = (int) ($this->request->getGet('store_id') ?? 0);
    $now       = date('Y-m-d H:i:s');
    if ($productId <= 0 || $storeId <= 0) {
      return $this->response->setStatusCode(400)->setJSON(['error' => 'product_id and store_id are required']);
    }

    $builder = $this->promoModel->builder();
    $builder->select('promos.id, promos.promo_code, promos.name, promos.type, promos.value')
      ->join('promoitems', 'promoitems.promo_id = promos.id', 'inner')
      ->where('promoitems.product_id', $productId)
      ->where('promos.store_id', $storeId)
      ->where('promos.start_datetime <=', $now)
      ->where('promos.end_datetime >=', $now);

    $rows = $builder->get()->getResultArray();
    return $this->response->setJSON(['data' => $rows]);
  }
}
