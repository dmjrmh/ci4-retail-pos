<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class PromoStoreSeeder extends Seeder
{
  public function run()
  {
    $promos = $this->db->table('promos')->select('id')->orderBy('id')->get()->getResultArray();
    $stores = $this->db->table('stores')->select('id')->orderBy('id')->get()->getResultArray();

    if (empty($promos) || empty($stores)) {
      return;
    }

    $data = [];

    // Attach first few stores to first promos (round-robin)
    $limit = min(8, count($stores));
    for ($i = 0; $i < $limit; $i++) {
      $promoId = $promos[$i % count($promos)]['id'];
      $storeId = $stores[$i]['id'];
      $data[] = [
        'promo_id'   => $promoId,
        'store_id'   => $storeId,
        'created_at' => Time::now(),
        'updated_at' => Time::now(),
      ];
    }

    if (! empty($data)) {
      $this->db->table('promostores')->insertBatch($data);
    }
  }
}

