<?php

namespace App\Models;

use CodeIgniter\Model;

class SaleModel extends Model
{
  protected $table            = 'sales';
  protected $primaryKey       = 'id';
  protected $useAutoIncrement = true;
  protected $returnType       = 'array';
  protected $useSoftDeletes   = true;
  protected $protectFields    = true;
  protected $allowedFields    = [
    'store_id','register_id','staff_id','receipt_no','sale_datetime',
    'total_items','subtotal','discount_total','tax_total','grand_total',
    'amount_paid','change_due','status','payment'
  ];

  protected $useTimestamps = true;
  protected $dateFormat    = 'datetime';
  protected $createdField  = 'created_at';
  protected $updatedField  = 'updated_at';
  protected $deletedField  = 'deleted_at';

  public function withJoins()
  {
    return $this
      ->select('sales.*, stores.store_name, registers.name as register_name, staffs.name as staff_name')
      ->join('stores', 'stores.id = sales.store_id', 'left')
      ->join('registers', 'registers.id = sales.register_id', 'left')
      ->join('staffs', 'staffs.id = sales.staff_id', 'left');
  }
}
