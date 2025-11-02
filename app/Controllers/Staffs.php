<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StaffModel;
use App\Models\StoreModel;

class Staffs extends BaseController
{
  protected $staffModel;
  protected $storeModel;
  protected $helpers = ['url', 'menu'];

  public function __construct()
  {
    $this->staffModel = new StaffModel();
    $this->storeModel = new StoreModel();
  }
  public function index()
  {
    $staffs = $this->staffModel
      ->select('staffs.*, stores.store_name')              // ambil nama store
      ->join('stores', 'stores.id = staffs.store_id', 'left')
      ->orderBy('staffs.id', 'DESC')
      ->paginate(10);
    $data = [
      'title' => 'Staff List',
      'staffs' => $staffs,
    ];
    return view('staffs/index', $data);
  }

  public function create()
  {
    $data = [
      'title' => 'Create Staff Form',
      'validation' => \Config\Services::validation(),
      'stores' => $this->storeModel->findAll(),
    ];

    return view('staffs/create', $data);
  }

  public function store()
  {
    $rules = [
      'store_id' => 'required',
      'staff_code' => 'required|min_length[2]|max_length[20]|is_unique[staffs.staff_code]',
      'name' => 'required|min_length[3]',
      'position' => 'required',
      'phone' => 'permit_empty|min_length[8]|max_length[15]'
    ];

    if (! $this->validate($rules)) {
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $this->staffModel->save($this->request->getPost());

    return redirect()->to('/staffs')->with('success', 'Staff added successfully');
  }

  public function edit($id)
  {
    $staff = $this->staffModel
      ->where('id', $id)
      ->first();

    if (! $staff) {
      return redirect()->to('/staffs')->with('error', 'Staff not found.');
    }

    $data = [
      'title'      => 'Edit Staff',
      'staff'      => $staff,
      'stores'     => $this->storeModel->findAll(),
      'validation' => \Config\Services::validation(),
    ];

    return view('staffs/edit', $data);
  }

  public function update($id)
  {
    $staff = $this->staffModel->find($id);
    if (! $staff) {
      return redirect()->to('/staffs')->with('error', 'Staff not found.');
    }

    $rules = [
      'store_id'   => 'required',
      'staff_code' => "required|min_length[2]|max_length[20]|is_unique[staffs.staff_code,id,{$id}]",
      'name'       => 'required|min_length[3]',
      'position'   => 'required',
      'phone'      => 'permit_empty|min_length[8]|max_length[15]',
    ];

    if (! $this->validate($rules)) {
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $data = [
      'id'         => $id,
      'store_id'   => $this->request->getPost('store_id'),
      'staff_code' => $this->request->getPost('staff_code'),
      'name'       => $this->request->getPost('name'),
      'position'   => $this->request->getPost('position'),
      'phone'      => $this->request->getPost('phone'),
    ];

    $this->staffModel->save($data);

    return redirect()->to('/staffs')->with('success', 'Staff updated successfully.');
  }


  public function delete($id)
  {
    $staff = $this->staffModel->find($id);

    if (! $staff) {
      return redirect()->to('/staffs')->with('error', 'Staff not found.');
    }

    $staffName = $staff['name'];

    $this->staffModel->delete($id);
    session()->setFlashdata('message', "Staff deleted successfully");
    return redirect()->to('/staffs')->with('success', 'Staff ' . $staffName . ' successfully deleted!');
  }
}
