<?php

namespace App\Models;

use CodeIgniter\Model;

class StoreModel extends Model
{
  protected $table            = 'stores';
  protected $primaryKey       = 'id';
  protected $useAutoIncrement = true;
  protected $insertID         = 0;
  protected $returnType       = 'array';
  protected $useSoftDeletes   = true;
  protected $protectFields    = true;
  protected $allowedFields    = [
    'store_name',
    'store_code',
    'address',
    'city',
  ];

  // Dates
  protected $useTimestamps = true;
  protected $dateFormat    = 'datetime';
  protected $createdField  = 'created_at';
  protected $updatedField  = 'updated_at';
  protected $deletedField  = 'deleted_at';

  public function store()
  {
    return $this->join('stores', 'stores.id = staffs.store_id', 'left')
      ->select('staffs.*, stores.store_name')
      ->findAll();
  }

  public function register()
  {
    return $this->join('stores', 'stores.id = registers.store_id', 'left')
      ->select('registers.*, stores.store_name')
      ->findAll();
  }
}
