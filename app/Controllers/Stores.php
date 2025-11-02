<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StoreModel;

class Stores extends BaseController
{
  protected $storeModel;
  protected $helpers = ['url', 'menu'];

  public function __construct()
  {
    $this->storeModel = new StoreModel();
  }

  public function index()
  {
    $stores = $this->storeModel->orderBy('id', 'DESC')->paginate(10);
    $data = [
      'title' => 'Store List',
      'stores' => $stores,
    ];
    return view('stores/index', $data);
  }

  public function create()
  {
    $data = [
      'title' => 'Create Store Form',
      'validation' => \Config\Services::validation(),
    ];

    return view('stores/create', $data);
  }

  public function store()
  {
    $rules = [
      'store_name' => 'required|min_length[3]|max_length[100]',
      'store_code' => 'required|min_length[2]|max_length[20]|is_unique[stores.store_code]',
      'address'    => 'permit_empty|string',
      'city'       => 'permit_empty|max_length[50]',
    ];

    if (! $this->validate($rules)) {
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $this->storeModel->insert([
      'store_name' => $this->request->getPost('store_name'),
      'store_code' => $this->request->getPost('store_code'),
      'address'    => $this->request->getPost('address'),
      'city'       => $this->request->getPost('city'),
    ]);

    return redirect()->to('/stores')->with('success', 'Store ' . $this->request->getPost('store_name') . ' successfully created!');
  }

  public function edit()
  {
    $data = [
      'title' => 'Edit Store Form',
      'validation' => \Config\Services::validation(),
      'store' => $this->storeModel->find($this->request->uri->getSegment(3)),
    ];
    return view('stores/edit', $data);
  }

  public function update($id)
  {
    $store = $this->storeModel->find($id);
    if (! $store) {
      return redirect()->to('/stores')->with('error', 'Store not found.');
    }

    $rules = [
      'store_name' => 'required|min_length[3]|max_length[100]',
      'store_code' => "required|min_length[2]|max_length[20]|is_unique[stores.store_code,id,{$id}]",
      'address'    => 'permit_empty|string',
      'city'       => 'permit_empty|max_length[50]',
    ];

    if (! $this->validate($rules)) {
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $this->storeModel->update($id, [
      'store_name' => $this->request->getPost('store_name'),
      'store_code' => $this->request->getPost('store_code'),
      'address'    => $this->request->getPost('address'),
      'city'       => $this->request->getPost('city'),
    ]);

    return redirect()->to('/stores')->with('success', 'Store ' . $this->request->getPost('store_name') . ' successfully updated!');
  }

  public function delete($id)
  {
    $store = $this->storeModel->find($id);

    if (! $store) {
      return redirect()->to('/stores')->with('error', 'Store not found.');
    }

    $storeName = $store['store_name'];

    $this->storeModel->delete($id);
    session()->setFlashdata('message', "Store deleted successfully");
    return redirect()->to('/stores')->with('success', 'Store ' . $storeName . ' successfully deleted!');
  }
}
