<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RegisterModel;
use App\Models\StoreModel;

class Registers extends BaseController
{
  protected $registerModel;
  protected $storeModel;
  protected $helpers = ['url', 'menu'];

  public function __construct()
  {
    $this->registerModel = new RegisterModel();
    $this->storeModel = new StoreModel();
  }
  public function index()
  {
    $registers = $this->registerModel
      ->select('registers.*, stores.store_name')
      ->join('stores', 'stores.id = registers.store_id', 'left')
      ->orderBy('registers.id', 'DESC')
      ->paginate(10);
    $data = [
      'title' => 'Register List',
      'registers' => $registers,
    ];
    return view('registers/index', $data);
  }

  public function create()
  {
    $data = [
      'title' => 'Create Register Form',
      'validation' => \Config\Services::validation(),
      'stores' => $this->storeModel->findAll(),
    ];

    return view('registers/create', $data);
  }

  public function store()
  {
    $rules = [
      'store_id' => 'required',
      'register_code' => 'required|min_length[2]|max_length[20]|is_unique[registers.register_code]',
      'name' => 'required|min_length[3]',
    ];

    if (! $this->validate($rules)) {
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $this->registerModel->save($this->request->getPost());

    return redirect()->to('/registers')->with('success', 'Register ' . $this->request->getPost('name') . ' added successfully');
  }

  public function edit($id)
  {
    $register = $this->registerModel
      ->where('id', $id)
      ->first();

    if (! $register) {
      return redirect()->to('/registers')->with('error', 'Register not found.');
    }

    $data = [
      'title'      => 'Edit Register',
      'register'      => $register,
      'stores'     => $this->storeModel->findAll(),
      'validation' => \Config\Services::validation(),
    ];

    return view('registers/edit', $data);
  }

  public function update($id)
  {
    $register = $this->registerModel->find($id);
    if (! $register) {
      return redirect()->to('/registers')->with('error', 'Register not found.');
    }

    $rules = [
      'store_id'   => 'required',
      'register_code' => "required|min_length[2]|max_length[20]|is_unique[registers.register_code,id,{$id}]",
      'name'       => 'required|min_length[3]',
    ];

    if (! $this->validate($rules)) {
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $data = [
      'id'         => $id,
      'store_id'   => $this->request->getPost('store_id'),
      'register_code' => $this->request->getPost('register_code'),
      'name'       => $this->request->getPost('name'),
    ];

    $this->registerModel->save($data);

    return redirect()->to('/registers')->with('success', 'Register updated successfully.');
  }

  public function delete($id)
  {
    $register = $this->registerModel->find($id);

    if (! $register) {
      return redirect()->to('/registers')->with('error', 'Register not found.');
    }

    $registerName = $register['name'];

    $this->registerModel->delete($id);
    session()->setFlashdata('message', "Register deleted successfully");
    return redirect()->to('/registers')->with('success', 'Register ' . $registerName . ' successfully deleted!');
  }

  // API: get registers by store (JSON)
  public function byStore()
  {
    $storeId = (int) ($this->request->getGet('store_id') ?? 0);
    if ($storeId <= 0) {
      return $this->response->setStatusCode(400)->setJSON(['error' => 'store_id is required']);
    }
    $rows = $this->registerModel
      ->select('id, name, register_code')
      ->where('store_id', $storeId)
      ->where('deleted_at', null)
      ->orderBy('name', 'ASC')
      ->findAll();
    return $this->response->setJSON(['data' => $rows]);
  }
}
