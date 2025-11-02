<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class StaffSeeder extends Seeder
{
  public function run()
  {
    $faker = \Faker\Factory::create('id_ID');
    $data = [
      [
        'store_id'   => 1,
        'staff_code' => 'STF001',
        'name'       => $faker->name(),
        'position'   => 'Kasir',
        'phone'      => '081234567890',
        'created_at' => Time::now(),
        'updated_at' => Time::now(),
      ],
      [
        'store_id'   => 1,
        'staff_code' => 'STF002',
        'name'       => $faker->name(),
        'position'   => 'Admin Gudang',
        'phone'      => '081233221122',
        'created_at' => Time::now(),
        'updated_at' => Time::now(),
      ],
      [
        'store_id'   => 2,
        'staff_code' => 'STF003',
        'name'       => $faker->name(),
        'position'   => 'Supervisor',
        'phone'      => '082132145678',
        'created_at' => Time::now(),
        'updated_at' => Time::now(),
      ],
      [
        'store_id'   => 2,
        'staff_code' => 'STF004',
        'name'       => $faker->name(),
        'position'   => 'Kasir',
        'phone'      => '089912318990',
        'created_at' => Time::now(),
        'updated_at' => Time::now(),
      ],
      [
        'store_id'   => 3,
        'staff_code' => 'STF005',
        'name'       => $faker->name(),
        'position'   => 'Kasir',
        'phone'      => '081355667788',
        'created_at' => Time::now(),
        'updated_at' => Time::now(),
      ],
      [
        'store_id'   => 4,
        'staff_code' => 'STF006',
        'name'       => $faker->name(),
        'position'   => 'Admin Toko',
        'phone'      => '081278965432',
        'created_at' => Time::now(),
        'updated_at' => Time::now(),
      ],
      [
        'store_id'   => 4,
        'staff_code' => 'STF007',
        'name'       => $faker->name(),
        'position'   => 'Kasir',
        'phone'      => '081234991122',
        'created_at' => Time::now(),
        'updated_at' => Time::now(),
      ],
    ];

    $this->db->table('staffs')->insertBatch($data);
  }
}
