<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;

class Products extends BaseController
{
  protected $productModel;
  protected $helpers = ['url', 'menu'];

  public function __construct()
  {
    $this->productModel = new ProductModel();
  }

  public function index()
  {
    $products = $this->productModel->orderBy('id', 'DESC')->paginate(10);
    $data = [
      'title' => 'Product List',
      'products' => $products,
    ];
    return view('products/index', $data);
  }

  public function create()
  {
    $data = [
      'title' => 'Create Product Form',
      'validation' => \Config\Services::validation(),
    ];

    return view('products/create', $data);
  }

  public function store()
  {
    $rules = [
      'name'          => 'required|min_length[2]|max_length[100]',
      'unit'          => 'required|max_length[20]',
      'cost_price'    => 'permit_empty|decimal',
      'selling_price' => 'permit_empty|decimal',
      'cover'         => 'permit_empty|is_image[cover]|mime_in[cover,image/jpg,image/jpeg,image/png]|max_size[cover,2048]',
    ];

    if (! $this->validate($rules)) {
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    // take picture
    $fileCover = $this->request->getFile('cover');

    if ($fileCover->getError() == 4) {
      $nameCover = 'missing-cover.png';
    } else {
      // get file cover
      $nameCover = $fileCover->getRandomName();

      // move to folder images
      $fileCover->move('images', $nameCover);
    }

    $this->productModel->insert([
      'name' => $this->request->getPost('name'),
      'unit' => $this->request->getPost('unit'),
      'cost_price'    => $this->request->getPost('cost_price'),
      'selling_price'       => $this->request->getPost('selling_price'),
      'cover' => $nameCover,
    ]);

    return redirect()->to('/products')->with('success', 'Product ' . $this->request->getPost('name') . ' successfully created!');
  }

  public function edit()
  {
    $data = [
      'title' => 'Edit Product Form',
      'validation' => \Config\Services::validation(),
      'product' => $this->productModel->find($this->request->uri->getSegment(3)),
    ];
    return view('products/edit', $data);
  }

  public function update($id)
  {
    $product = $this->productModel->find($id);
    if (! $product) {
      return redirect()->to('/products')->with('error', 'Product not found.');
    }

    $rules = [
      'name'          => 'required|min_length[2]|max_length[100]',
      'unit'          => 'required|max_length[20]',
      'cost_price'    => 'permit_empty|decimal',
      'selling_price' => 'permit_empty|decimal',
      'cover'         => 'permit_empty|is_image[cover]|mime_in[cover,image/jpg,image/jpeg,image/png]|max_size[cover,2048]',
    ];

    if (! $this->validate($rules)) {
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    // take picture
    $fileCover = $this->request->getFile('cover');
    $nameCover = $product['cover'];

    if ($fileCover->getError() != 4) {
      // get file cover
      $nameCover = $fileCover->getRandomName();

      // move to folder images
      $fileCover->move('images', $nameCover);

      // delete old cover if not default
      if ($product['cover'] != 'missing-cover.png') {
        unlink('images/' . $product['cover']);
      }
    }

    $this->productModel->update($id, [
      'name' => $this->request->getPost('name'),
      'unit' => $this->request->getPost('unit'),
      'cost_price'    => $this->request->getPost('cost_price'),
      'selling_price'       => $this->request->getPost('selling_price'),
      'cover' => $nameCover,
    ]);

    return redirect()->to('/products')->with('success', 'Product ' . $this->request->getPost('name') . ' successfully updated!');
  }

  public function delete($id)
  {
    $product = $this->productModel->find($id);

    // check if cover is default
    if ($product['cover'] != 'missing-cover.png') {
      unlink('images/' . $product['cover']);
    }


    $this->productModel->delete($id);
    session()->setFlashdata('message', "Product deleted successfully");
    return redirect()->to('/products');
  }
}
