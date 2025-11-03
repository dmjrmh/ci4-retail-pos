<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class PromoItemSeeder extends Seeder
{
  public function run()
  {
    // Fetch some promo and product IDs
    $promos   = $this->db->table('promos')->select('id')->orderBy('id')->get()->getResultArray();
    $products = $this->db->table('products')->select('id')->orderBy('id')->get()->getResultArray();

    if (empty($promos) || empty($products)) {
      return;
    }

    $data = [];
    $count = min(12, count($products));
    for ($i = 0; $i < $count; $i++) {
      $promoId   = $promos[$i % count($promos)]['id'];
      $productId = $products[$i]['id'];
      $data[] = [
        'promo_id'   => $promoId,
        'product_id' => $productId,
        'created_at' => Time::now(),
        'updated_at' => Time::now(),
      ];
    }

    if (! empty($data)) {
      // table name: promoitems (without underscore)
      $this->db->table('promoitems')->insertBatch($data);
    }
  }
}
