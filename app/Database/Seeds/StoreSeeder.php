<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class StoreSeeder extends Seeder
{
  public function run()
  {
    $data = [
      [
        'store_name' => 'Toko Pusat Surabaya',
        'store_code' => 'SBY01',
        'address'    => 'Jl. Tunjungan No. 88, Surabaya',
        'city'       => 'Surabaya',
        'created_at' => Time::now(),
        'updated_at' => Time::now(),
      ],
      [
        'store_name' => 'Cabang Sidoarjo',
        'store_code' => 'SDA01',
        'address'    => 'Jl. Raya Aloha No. 21, Sidoarjo',
        'city'       => 'Sidoarjo',
        'created_at' => Time::now(),
        'updated_at' => Time::now(),
      ],
      [
        'store_name' => 'Cabang Malang',
        'store_code' => 'MLG01',
        'address'    => 'Jl. Soekarno-Hatta No. 55, Malang',
        'city'       => 'Malang',
        'created_at' => Time::now(),
        'updated_at' => Time::now(),
      ],
      [
        'store_name' => 'Cabang Gresik',
        'store_code' => 'GRS01',
        'address'    => 'Jl. Dr. Wahidin Sudirohusodo No. 9, Gresik',
        'city'       => 'Gresik',
        'created_at' => Time::now(),
        'updated_at' => Time::now(),
      ],
    ];

    $this->db->table('stores')->insertBatch($data);
  }
}
