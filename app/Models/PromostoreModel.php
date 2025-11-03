<?php

namespace App\Models;

use CodeIgniter\Model;

class PromostoreModel extends Model
{
  protected $table            = 'promostores';
  protected $primaryKey       = 'id';
  protected $useAutoIncrement = true;
  protected $insertID         = 0;
  protected $returnType       = 'array';
  protected $useSoftDeletes   = true;
  protected $protectFields    = true;
  protected $allowedFields    = ['promo_id', 'store_id'];

  // Dates
  protected $useTimestamps = true;
  protected $dateFormat    = 'datetime';
  protected $createdField  = 'created_at';
  protected $updatedField  = 'updated_at';
  protected $deletedField  = 'deleted_at';

  public function withJoins()
  {
    return $this
      ->select('promostores.*, promos.name AS promo_name, stores.store_name')
      ->join('promos', 'promos.id = promostores.promo_id', 'left')
      ->join('stores', 'stores.id = promostores.store_id', 'left');
  }
}
