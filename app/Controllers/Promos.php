<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PromoModel;

class Promos extends BaseController
{
  protected $promoModel;
  protected $helpers = ['url', 'menu'];
  public function __construct()
  {
    $this->promoModel = new PromoModel();
  }

  public function index()
  {
    $promos = $this->promoModel->orderBy('id', 'DESC')->paginate(10);
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
    ];

    return view('promos/create', $data);
  }

  public function store()
  {
    $rules = [
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

    $this->promoModel->save($this->request->getPost());

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

    $rules = [
      'promo_code'      => 'required|min_length[2]|max_length[20]|is_unique[promos.promo_code]',
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
      'promo_code'     => $this->request->getPost('promo_code'),
      'type'           => $this->request->getPost('type'),
      'name'           => $this->request->getPost('name'),
      'value'          => $this->request->getPost('value'),
      'start_datetime' => $this->request->getPost('start_datetime'),
      'end_datetime'   => $this->request->getPost('end_datetime'),
    ];

    $this->promoModel->save($data);

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
}
