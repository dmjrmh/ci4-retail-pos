<?php

namespace App\Models;

use CodeIgniter\Model;

class PromoitemModel extends Model
{
  protected $table            = 'promoitems';
  protected $primaryKey       = 'id';
  protected $useAutoIncrement = true;
  protected $insertID         = 0;
  protected $returnType       = 'array';
  protected $useSoftDeletes   = true;
  protected $protectFields    = true;
  protected $allowedFields    = ['promo_id', 'product_id'];

  // Dates
  protected $useTimestamps = true;
  protected $dateFormat    = 'datetime';
  protected $createdField  = 'created_at';
  protected $updatedField  = 'updated_at';
  protected $deletedField  = 'deleted_at';

  public function withJoins()
  {
    return $this
      ->select('promoitems.*, promos.name AS promo_name, products.name AS product_name, stores.store_name')
      ->join('promos', 'promos.id = promoitems.promo_id', 'left')
      ->join('products', 'products.id = promoitems.product_id', 'left')
      ->join('stores', 'stores.id = promos.store_id', 'left')
      ->where('promoitems.deleted_at', null);
  }
}
