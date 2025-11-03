<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PromoModel;
use App\Models\ProductModel;
use App\Models\PromoitemModel;

class Promoitems extends BaseController
{
  protected $promoModel;
  protected $promoItemModel;
  protected $productModel;
  protected $helpers = ['url', 'menu'];

  public function __construct()
  {
    $this->promoModel = new PromoModel();
    $this->promoItemModel = new PromoitemModel();
    $this->productModel = new ProductModel();
  }
  public function index()
  {
    $promoitems = $this->promoItemModel
      ->withJoins()
      ->orderBy('promoitems.id', 'DESC')
      ->paginate(10);

    $data = [
      'title'       => 'Promo Items',
      'promoitems'  => $promoitems,
      'pager'       => $this->promoItemModel->pager,
    ];

    return view('promoitems/index', $data);
  }

  public function create()
  {
    $data = [
      'title'      => 'Create Promo Item',
      'validation' => \Config\Services::validation(),
      'promos'     => $this->promoModel->orderBy('name', 'ASC')->findAll(),
      'products'   => $this->productModel->orderBy('name', 'ASC')->findAll(),
    ];

    return view('promoitems/create', $data);
  }

  public function store()
  {
    $rules = [
      'promo_id'   => 'required',
      'product_id' => 'required',
    ];

    if (! $this->validate($rules)) {
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $promoId   = (int) $this->request->getPost('promo_id');
    $productId = (int) $this->request->getPost('product_id');

    $exists = $this->promoItemModel
      ->where('promo_id', $promoId)
      ->where('product_id', $productId)
      ->first();

    if ($exists) {
      return redirect()->back()->withInput()->with('errors', [
        'unique' => 'This product is already attached to the selected promo.',
      ]);
    }

    $this->promoItemModel->insert([
      'promo_id'   => $promoId,
      'product_id' => $productId,
    ]);

    return redirect()->to('/promoitems')->with('success', 'Promo item added successfully');
  }

  public function edit($id)
  {
    $promoitem = $this->promoItemModel->find($id);
    if (! $promoitem) {
      return redirect()->to('/promoitems')->with('error', 'Promo item not found.');
    }

    $data = [
      'title'      => 'Edit Promo Item',
      'promoitem'  => $promoitem,
      'promos'     => $this->promoModel->orderBy('name', 'ASC')->findAll(),
      'products'   => $this->productModel->orderBy('name', 'ASC')->findAll(),
      'validation' => \Config\Services::validation(),
    ];

    return view('promoitems/edit', $data);
  }

  public function update($id)
  {
    $promoitem = $this->promoItemModel->find($id);
    if (! $promoitem) {
      return redirect()->to('/promoitems')->with('error', 'Promo item not found.');
    }

    $rules = [
      'promo_id'   => 'required',
      'product_id' => 'required',
    ];

    if (! $this->validate($rules)) {
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $promoId   = (int) $this->request->getPost('promo_id');
    $productId = (int) $this->request->getPost('product_id');

    $exists = $this->promoItemModel
      ->where('promo_id', $promoId)
      ->where('product_id', $productId)
      ->where('id !=', $id)
      ->first();

    if ($exists) {
      return redirect()->back()->withInput()->with('errors', [
        'unique' => 'This product is already attached to the selected promo.',
      ]);
    }

    $this->promoItemModel->update($id, [
      'promo_id'   => $promoId,
      'product_id' => $productId,
    ]);

    return redirect()->to('/promoitems')->with('success', 'Promo item updated successfully');
  }

  public function delete($id)
  {
    $promoitem = $this->promoItemModel->find($id);
    if (! $promoitem) {
      return redirect()->to('/promoitems')->with('error', 'Promo item not found.');
    }

    $this->promoItemModel->delete($id);
    return redirect()->to('/promoitems')->with('success', 'Promo item deleted successfully');
  }
}
