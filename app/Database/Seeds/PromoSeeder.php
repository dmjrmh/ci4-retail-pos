<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class PromoSeeder extends Seeder
{
  public function run()
  {
    $data = [
      [
        'name'            => 'Diskon Akhir Tahun',
        'promo_code'      => 'PRM001',
        'type'            => 'percent',
        'value'           => 20,
        'start_datetime'  => Time::parse('2025-12-01 00:00:00'),
        'end_datetime'    => Time::parse('2025-12-31 23:59:59'),
        'created_at'      => Time::now(),
        'updated_at'      => Time::now(),
      ],
      [
        'name'            => 'Promo Belanja 100 Ribu',
        'promo_code'      => 'PRM002',
        'type'            => 'fixed',
        'value'           => 15000,
        'start_datetime'  => Time::parse('2025-11-03 00:00:00'),
        'end_datetime'    => Time::parse('2025-11-17 22:00:00'),
        'created_at'      => Time::now(),
        'updated_at'      => Time::now(),
      ],
      [
        'name'            => 'Midweek Sale',
        'promo_code'      => 'PRM003',
        'type'            => 'percent',
        'value'           => 10,
        'start_datetime'  => Time::parse('2025-11-02 00:00:00'),
        'end_datetime'    => Time::parse('2025-11-05 22:00:00'),
        'created_at'      => Time::now(),
        'updated_at'      => Time::now(),
      ],
    ];

    $this->db->table('promos')->insertBatch($data);
  }
}
