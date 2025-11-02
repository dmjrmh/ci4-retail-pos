<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class RegisterSeeder extends Seeder
{
  public function run()
  {
    $data = [
      [
        'store_id'      => 1,
        'register_code' => 'REG-SBY-01',
        'name'          => 'Kasir 1 - Surabaya',
        'created_at'    => Time::now(),
        'updated_at'    => Time::now(),
      ],
      [
        'store_id'      => 1,
        'register_code' => 'REG-SBY-02',
        'name'          => 'Kasir 2 - Surabaya',
        'created_at'    => Time::now(),
        'updated_at'    => Time::now(),
      ],
      [
        'store_id'      => 2,
        'register_code' => 'REG-SDA-01',
        'name'          => 'Kasir 1 - Sidoarjo',
        'created_at'    => Time::now(),
        'updated_at'    => Time::now(),
      ],
      [
        'store_id'      => 2,
        'register_code' => 'REG-SDA-02',
        'name'          => 'Kasir 2 - Sidoarjo',
        'created_at'    => Time::now(),
        'updated_at'    => Time::now(),
      ],
      [
        'store_id'      => 3,
        'register_code' => 'REG-MLG-01',
        'name'          => 'Kasir 1 - Malang',
        'created_at'    => Time::now(),
        'updated_at'    => Time::now(),
      ],
      [
        'store_id'      => 3,
        'register_code' => 'REG-MLG-02',
        'name'          => 'Kasir 2 - Malang',
        'created_at'    => Time::now(),
        'updated_at'    => Time::now(),
      ],
      [
        'store_id'      => 3,
        'register_code' => 'REG-MLG-03',
        'name'          => 'Kasir 3 - Malang',
        'created_at'    => Time::now(),
        'updated_at'    => Time::now(),
      ],
      [
        'store_id'      => 4,
        'register_code' => 'REG-GRS-01',
        'name'          => 'Kasir 1 - Gresik',
        'created_at'    => Time::now(),
        'updated_at'    => Time::now(),
      ],
    ];

    $this->db->table('registers')->insertBatch($data);
  }
}
