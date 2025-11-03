<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PromoModel;
use App\Models\StoreModel;
use App\Models\PromostoreModel;

class Promostores extends BaseController
{
  protected $promoModel;
  protected $storeModel;
  protected $promostoreModel;
  protected $helpers = ['url', 'menu'];

  public function __construct()
  {
    $this->promoModel = new PromoModel();
    $this->storeModel = new StoreModel();
    $this->promostoreModel = new PromostoreModel();
  }

  public function index()
  {
    $promostores = $this->promostoreModel
      ->withJoins()
      ->orderBy('promostores.id', 'DESC')
      ->paginate(10);

    $data = [
      'title'        => 'Promo Stores',
      'promostores'  => $promostores,
      'pager'        => $this->promostoreModel->pager,
    ];

    return view('promostores/index', $data);
  }

  public function create()
  {
    $data = [
      'title'      => 'Create Promo Store',
      'validation' => \Config\Services::validation(),
      'promos'     => $this->promoModel->orderBy('name', 'ASC')->findAll(),
      'stores'     => $this->storeModel->orderBy('store_name', 'ASC')->findAll(),
    ];

    return view('promostores/create', $data);
  }

  public function store()
  {
    $rules = [
      'promo_id' => 'required',
      'store_id' => 'required',
    ];

    if (! $this->validate($rules)) {
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $promoId = (int) $this->request->getPost('promo_id');
    $storeId = (int) $this->request->getPost('store_id');

    $exists = $this->promostoreModel
      ->where('promo_id', $promoId)
      ->where('store_id', $storeId)
      ->first();

    if ($exists) {
      return redirect()->back()->withInput()->with('errors', [
        'unique' => 'This store is already attached to the selected promo.',
      ]);
    }

    $this->promostoreModel->insert([
      'promo_id' => $promoId,
      'store_id' => $storeId,
    ]);

    return redirect()->to('/promostores')->with('success', 'Promo store added successfully');
  }

  public function edit($id)
  {
    $promostore = $this->promostoreModel->find($id);
    if (! $promostore) {
      return redirect()->to('/promostores')->with('error', 'Promo store not found.');
    }

    $data = [
      'title'       => 'Edit Promo Store',
      'promostore'  => $promostore,
      'promos'      => $this->promoModel->orderBy('name', 'ASC')->findAll(),
      'stores'      => $this->storeModel->orderBy('store_name', 'ASC')->findAll(),
      'validation'  => \Config\Services::validation(),
    ];

    return view('promostores/edit', $data);
  }

  public function update($id)
  {
    $promostore = $this->promostoreModel->find($id);
    if (! $promostore) {
      return redirect()->to('/promostores')->with('error', 'Promo store not found.');
    }

    $rules = [
      'promo_id' => 'required',
      'store_id' => 'required',
    ];

    if (! $this->validate($rules)) {
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $promoId = (int) $this->request->getPost('promo_id');
    $storeId = (int) $this->request->getPost('store_id');

    $exists = $this->promostoreModel
      ->where('promo_id', $promoId)
      ->where('store_id', $storeId)
      ->where('id !=', $id)
      ->first();

    if ($exists) {
      return redirect()->back()->withInput()->with('errors', [
        'unique' => 'This store is already attached to the selected promo.',
      ]);
    }

    $this->promostoreModel->update($id, [
      'promo_id' => $promoId,
      'store_id' => $storeId,
    ]);

    return redirect()->to('/promostores')->with('success', 'Promo store updated successfully');
  }

  public function delete($id)
  {
    $promostore = $this->promostoreModel->find($id);
    if (! $promostore) {
      return redirect()->to('/promostores')->with('error', 'Promo store not found.');
    }

    $this->promostoreModel->delete($id);
    return redirect()->to('/promostores')->with('success', 'Promo store deleted successfully');
  }
}
