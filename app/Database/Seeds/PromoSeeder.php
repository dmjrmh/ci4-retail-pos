<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class PromoSeeder extends Seeder
{
  public function run()
  {
    // get some store ids
    $stores = $this->db->table('stores')->select('id')->orderBy('id')->get()->getResultArray();
    if (empty($stores)) {
      return;
    }

    $now   = Time::now();
    $today = Time::parse(date('Y-m-d 00:00:00'));

    $data = [];
    // three sample promos, distributed to first stores
    $data[] = [
      'store_id'       => $stores[0]['id'],
      'name'           => 'Diskon Akhir Tahun',
      'promo_code'     => 'PRM001',
      'type'           => 'percent',
      'value'          => 20,
      'start_datetime' => $today->subDays(1),
      'end_datetime'   => $today->addDays(30),
      'created_at'     => $now,
      'updated_at'     => $now,
    ];
    $data[] = [
      'store_id'       => $stores[count($stores) > 1 ? 1 : 0]['id'],
      'name'           => 'Promo Belanja 100 Ribu',
      'promo_code'     => 'PRM002',
      'type'           => 'fixed',
      'value'          => 15000,
      'start_datetime' => $today->subDays(2),
      'end_datetime'   => $today->addDays(14),
      'created_at'     => $now,
      'updated_at'     => $now,
    ];
    $data[] = [
      'store_id'       => $stores[count($stores) > 2 ? 2 : 0]['id'],
      'name'           => 'Midweek Sale',
      'promo_code'     => 'PRM003',
      'type'           => 'percent',
      'value'          => 10,
      'start_datetime' => $today->subDays(3),
      'end_datetime'   => $today->addDays(7),
      'created_at'     => $now,
      'updated_at'     => $now,
    ];

    // expired promos (examples that should not appear in sales eligibility)
    $data[] = [
      'store_id'       => $stores[0]['id'],
      'name'           => 'Weekend Flash (Expired)',
      'promo_code'     => 'PRM004',
      'type'           => 'percent',
      'value'          => 15,
      'start_datetime' => '2023-05-20 00:00:00',
      'end_datetime'   => '2023-05-30 23:59:59',
      'created_at'     => $now,
      'updated_at'     => $now,
    ];
    $data[] = [
      'store_id'       => $stores[count($stores) > 1 ? 1 : 0]['id'],
      'name'           => 'Ramadhan 2023 (Expired)',
      'promo_code'     => 'PRM005',
      'type'           => 'fixed',
      'value'          => 10000,
      'start_datetime' => '2023-03-23 00:00:00',
      'end_datetime'   => '2023-04-21 23:59:59',
      'created_at'     => $now,
      'updated_at'     => $now,
    ];
    $data[] = [
      'store_id'       => $stores[count($stores) > 2 ? 2 : 0]['id'],
      'name'           => 'Early Bird Jan (Expired)',
      'promo_code'     => 'PRM006',
      'type'           => 'percent',
      'value'          => 5,
      'start_datetime' => '2023-01-01 00:00:00',
      'end_datetime'   => '2023-01-15 23:59:59',
      'created_at'     => $now,
      'updated_at'     => $now,
    ];

    $this->db->table('promos')->insertBatch($data);
  }
}

