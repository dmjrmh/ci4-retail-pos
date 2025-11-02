<?php

namespace App\Models;

use CodeIgniter\Model;

class StaffModel extends Model
{
  protected $table            = 'staffs';
  protected $primaryKey       = 'id';
  protected $useAutoIncrement = true;
  protected $insertID         = 0;
  protected $returnType       = 'array';
  protected $useSoftDeletes   = true;
  protected $protectFields    = true;
  protected $allowedFields    = [
    'store_id',
    'staff_code',
    'name',
    'position',
    'phone',
  ];

  // Dates
  protected $useTimestamps = true;
  protected $dateFormat    = 'datetime';
  protected $createdField  = 'created_at';
  protected $updatedField  = 'updated_at';
  protected $deletedField  = 'deleted_at';

  public function getStore($staffId)
  {
    return $this->select('stores.store_name')
      ->join('stores', 'stores.id = staffs.store_id', 'left')
      ->where('staffs.id', $staffId)
      ->first();
  }
}
