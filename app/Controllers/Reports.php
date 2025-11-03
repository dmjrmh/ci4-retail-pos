<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StoreModel;
use App\Models\StaffModel;

class Reports extends BaseController
{
  protected $helpers = ['url', 'menu'];

  public function index()
  {
    $from = $this->request->getGet('from') ?: date('Y-m-01');
    $to   = $this->request->getGet('to')   ?: date('Y-m-d');
    $storeId = $this->request->getGet('store_id');
    $staffId = $this->request->getGet('staff_id');

    $fromStart = $from . ' 00:00:00';
    $toEnd     = $to   . ' 23:59:59';

    $db = db_connect();
    $b = $db->table('sales')
      ->select(
        "stores.store_name AS store,
                COALESCE(staffs.name, '-') AS salesperson,
                COUNT(sales.id) AS total_tx,
                COALESCE(SUM(sales.total_items),0) AS total_qty,
                COALESCE(SUM(sales.subtotal),0) AS subtotal,
                COALESCE(SUM(sales.discount_total),0) AS discount,
                COALESCE(SUM(sales.grand_total),0) AS net"
      )
      ->join('stores', 'stores.id = sales.store_id', 'left')
      ->join('staffs', 'staffs.id = sales.staff_id', 'left')
      ->where('sales.deleted_at', null)
      ->where('sales.sale_datetime >=', $fromStart)
      ->where('sales.sale_datetime <=', $toEnd);

    if (!empty($storeId)) {
      $b->where('sales.store_id', (int) $storeId);
    }
    if (!empty($staffId)) {
      $b->where('sales.staff_id', (int) $staffId);
    }

    $rows = $b->groupBy('sales.store_id, sales.staff_id')
      ->orderBy('stores.store_name', 'ASC')
      ->orderBy('staffs.name', 'ASC')
      ->get()->getResultArray();

    // Detail items per transaksi (untuk tampilan)
    $d = $db->table('sales')
      ->select("sales.id as sale_id, sales.receipt_no, sales.sale_datetime, 
                     stores.store_name as store, COALESCE(staffs.name, '-') as salesperson, 
                     saleitems.product_name, saleitems.qty, saleitems.price, saleitems.discount, saleitems.line_total")
      ->join('stores', 'stores.id = sales.store_id', 'left')
      ->join('staffs', 'staffs.id = sales.staff_id', 'left')
      ->join('saleitems', 'saleitems.sale_id = sales.id', 'inner')
      ->where('sales.deleted_at', null)
      ->where('sales.sale_datetime >=', $fromStart)
      ->where('sales.sale_datetime <=', $toEnd);
    if (!empty($storeId)) {
      $d->where('sales.store_id', (int) $storeId);
    }
    if (!empty($staffId)) {
      $d->where('sales.staff_id', (int) $staffId);
    }
    $detailRows = $d->orderBy('stores.store_name', 'ASC')
      ->orderBy('staffs.name', 'ASC')
      ->orderBy('sales.sale_datetime', 'ASC')
      ->orderBy('saleitems.product_name', 'ASC')
      ->get()->getResultArray();

    // Grouping: key per store|salesperson -> sale_id -> items
    $detailsByGroup = [];
    foreach ($detailRows as $r) {
      $g = $r['store'] . '|' . $r['salesperson'];
      if (!isset($detailsByGroup[$g])) {
        $detailsByGroup[$g] = [];
      }
      $sid = (int) $r['sale_id'];
      if (!isset($detailsByGroup[$g][$sid])) {
        $detailsByGroup[$g][$sid] = [
          'sale_id' => $sid,
          'receipt_no' => $r['receipt_no'],
          'sale_datetime' => $r['sale_datetime'],
          'items' => [],
        ];
      }
      $detailsByGroup[$g][$sid]['items'][] = [
        'product_name' => $r['product_name'],
        'qty' => (float) $r['qty'],
        'price' => (float) $r['price'],
        'discount' => (float) $r['discount'],
        'line_total' => (float) $r['line_total'],
      ];
    }

    $storeModel = new StoreModel();
    $staffModel = new StaffModel();

    $stores = $storeModel->select('id, store_name')->orderBy('store_name', 'ASC')->findAll();
    $staffsQ = $staffModel->select('id, name')->orderBy('name', 'ASC');
    if (!empty($storeId)) {
      $staffsQ->where('store_id', (int) $storeId);
    }
    $salespeople = $staffsQ->findAll();

    return view('reports/index', [
      'title' => 'Sales Reports',
      'from' => $from,
      'to' => $to,
      'store_id' => $storeId,
      'staff_id' => $staffId,
      'stores' => $stores,
      'salespeople' => $salespeople,
      'rows' => $rows,
      'detailsByGroup' => $detailsByGroup,
    ]);
  }
}
