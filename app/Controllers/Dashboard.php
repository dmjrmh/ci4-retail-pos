<?php

namespace App\Controllers;

use App\Models\StoreModel;
use App\Models\StaffModel;
use App\Models\SaleModel;
use App\Models\ProductModel;
use App\Models\PromoModel;

class Dashboard extends BaseController
{
    protected $helpers = ['url', 'menu'];
    
    public function index()
    {
        $storeModel = new StoreModel();
        $staffModel = new StaffModel();
        $saleModel = new SaleModel();
        $productModel = new ProductModel();
        $promoModel = new PromoModel();

        $data = [
            'title' => 'Dashboard',
            'counts' => [
                'stores'   => $storeModel->where('deleted_at', null)->countAllResults(),
                'staffs'   => $staffModel->where('deleted_at', null)->countAllResults(),
                'sales'    => $saleModel->where('deleted_at', null)->countAllResults(),
                'products' => $productModel->where('deleted_at', null)->countAllResults(),
                'promos'   => $promoModel->where('deleted_at', null)->countAllResults(),
            ],
        ];

        return view('dashboard/index', $data);
    }
}

