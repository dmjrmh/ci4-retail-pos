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

    $this->db->table('promos')->insertBatch($data);
  }
}
