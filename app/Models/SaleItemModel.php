<?php

namespace App\Models;

use CodeIgniter\Model;

class SaleItemModel extends Model
{
  protected $table            = 'saleitems';
  protected $primaryKey       = 'id';
  protected $useAutoIncrement = true;
  protected $returnType       = 'array';
  protected $useSoftDeletes   = true;
  protected $protectFields    = true;
  protected $allowedFields    = [
    'sale_id','product_id','product_name','unit','qty','price','discount','tax','line_total','applied_promo_id','applied_promo_code'
  ];

  protected $useTimestamps = true;
  protected $dateFormat    = 'datetime';
  protected $createdField  = 'created_at';
  protected $updatedField  = 'updated_at';
  protected $deletedField  = 'deleted_at';
}

