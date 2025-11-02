<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class ProductSeeder extends Seeder
{
  public function run()
  {
    $data = [
      [
        'name'          => 'Air Mineral 600ml',
        'unit'          => 'Botol',
        'cost_price'    => 2500.00,
        'selling_price' => 3500.00,
        'tax_included'  => true,
        'cover'         => 'air-mineral.jpg',
        'created_at'    => Time::now(),
        'updated_at'    => Time::now(),
      ],
      [
        'name'          => 'Roti Tawar Kupas',
        'unit'          => 'Bungkus',
        'cost_price'    => 12000.00,
        'selling_price' => 15000.00,
        'tax_included'  => true,
        'cover'         => 'roti-tawar.jpg',
        'created_at'    => Time::now(),
        'updated_at'    => Time::now(),
      ],
      [
        'name'          => 'Minyak Goreng 1L',
        'unit'          => 'Botol',
        'cost_price'    => 16000.00,
        'selling_price' => 18500.00,
        'tax_included'  => true,
        'cover'         => 'minyak-goreng.jpg',
        'created_at'    => Time::now(),
        'updated_at'    => Time::now(),
      ],
      [
        'name'          => 'Gula Pasir 1kg',
        'unit'          => 'Kg',
        'cost_price'    => 14500.00,
        'selling_price' => 17000.00,
        'tax_included'  => true,
        'cover'         => 'gula-pasir.jpg',
        'created_at'    => Time::now(),
        'updated_at'    => Time::now(),
      ],
      [
        'name'          => 'Kopi Instan Sachet',
        'unit'          => 'Sachet',
        'cost_price'    => 1500.00,
        'selling_price' => 2500.00,
        'tax_included'  => false,
        'cover'         => 'kopi-instan.jpg',
        'created_at'    => Time::now(),
        'updated_at'    => Time::now(),
      ],
    ];

    $this->db->table('products')->insertBatch($data);
  }
}
