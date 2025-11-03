<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SaleModel;
use App\Models\SaleItemModel;
use App\Models\ProductModel;
use App\Models\PromoModel;
use App\Models\StoreModel;
use App\Models\RegisterModel;
use App\Models\StaffModel;

class Sales extends BaseController
{
  protected $helpers = ['url', 'menu'];
  protected $storeModel;
  protected $registerModel;
  protected $staffModel;
  protected $productModel;
  protected $promoModel;
  protected $saleModel;
  protected $saleItemModel;

  public function __construct(){
    $this->storeModel = new StoreModel();
    $this->registerModel = new RegisterModel();
    $this->staffModel = new StaffModel();
    $this->productModel = new ProductModel();
    $this->promoModel = new PromoModel();
    $this->saleModel = new SaleModel();
    $this->saleItemModel = new SaleItemModel();
  }

  public function index()
  {
    $sales = $this->saleModel->withJoins()->orderBy('sales.id', 'DESC')->paginate(10);
    $data = [
      'title' => 'Sales',
      'sales' => $sales,
      'pager' => $this->saleModel->pager,
    ];
    return view('sales/index', $data);
  }

  public function create()
  {
    $stores = $this->storeModel->orderBy('store_name','ASC')->findAll();
    $defaultStoreId = $stores[0]['id'] ?? null;
    $registersQ = $this->registerModel->orderBy('name','ASC');
    if ($defaultStoreId) { $registersQ->where('store_id', $defaultStoreId); }
    $registers = $registersQ->findAll();
    $staffsQ =$this->staffModel->orderBy('name','ASC');
    if ($defaultStoreId) { $staffsQ->where('store_id', $defaultStoreId); }
    $staffs = $staffsQ->findAll();
    $products = $this->productModel->orderBy('name','ASC')->findAll();

    $data = [
      'title' => 'Create Sale',
      'stores' => $stores,
      'registers' => $registers,
      'staffs' => $staffs,
      'products' => $products,
      'defaultStoreId' => $defaultStoreId,
    ];
    return view('sales/create', $data);
  }

  public function store()
  {
    $payload = $this->request->getJSON(true) ?? $this->request->getPost();
    if (!isset($payload['items']) && $this->request->getPost('product_id')) {
      $productIds = (array) $this->request->getPost('product_id');
      $qtys       = (array) $this->request->getPost('qty');
      $prices     = (array) $this->request->getPost('price');
      $promoIds   = (array) $this->request->getPost('promo_id');
      $items = [];
      foreach ($productIds as $i => $pid) {
        if (!$pid) continue;
        $items[] = [
          'product_id' => (int) $pid,
          'qty'        => (float) ($qtys[$i] ?? 1),
          'price'      => (float) ($prices[$i] ?? 0),
          'promo_id'   => !empty($promoIds[$i]) ? (int) $promoIds[$i] : null,
        ];
      }
      $payload['items'] = $items;
      if (!isset($payload['payments'])) {
        $payload['payments'] = [[
          'method' => (string) ($this->request->getPost('payment_method') ?? 'cash'),
          'amount' => (float) ($this->request->getPost('amount_paid') ?? 0),
        ]];
      }
    }

    $storeId    = (int) ($payload['store_id'] ?? 0);
    $registerId = (int) ($payload['register_id'] ?? 0);
    $staffId    = $payload['staff_id'] ?? null;
    $items      = $payload['items'] ?? [];
    $payments   = $payload['payments'] ?? [];
    $now        = date('Y-m-d H:i:s');

    if ($storeId <= 0 || $registerId <= 0 || empty($items)) {
      return $this->respondOrRedirectError('Invalid sale payload');
    }

    $productModel = $this->productModel;
    $promoModel   = $this->promoModel;
    $saleModel    = $this->saleModel;
    $itemModel    = $this->saleItemModel;

    // Build receipt number as STORECODE/MMYYYY/NNNN (sequence per store per month)
    $store = $this->storeModel->select('store_code')->find($storeId);
    $storeCode = strtoupper($store['store_code'] ?? ('ST'.$storeId));
    $prefix = sprintf('%s/%s/', $storeCode, date('mY'));
    $last = $this->saleModel->select('receipt_no')
      ->where('store_id', $storeId)
      ->like('receipt_no', $prefix, 'after')
      ->orderBy('id', 'DESC')
      ->first();
    $seq = 1;
    if ($last && !empty($last['receipt_no'])) {
      $parts = explode('/', $last['receipt_no']);
      $lastSeq = (int) end($parts);
      if ($lastSeq > 0) { $seq = $lastSeq + 1; }
    }
    // Ensure uniqueness: bump until not found
    do {
      $receiptNo = $prefix . str_pad((string)$seq, 4, '0', STR_PAD_LEFT);
      $exists = $this->saleModel->where('receipt_no', $receiptNo)->countAllResults();
      if ($exists == 0) break;
      $seq++;
    } while (true);

    $totals = [ 'total_items'=>0, 'subtotal'=>0.0, 'discount_total'=>0.0, 'tax_total'=>0.0, 'grand_total'=>0.0 ];
    $lineRows = [];

    foreach ($items as $row) {
      $product = $productModel->find($row['product_id'] ?? 0);
      if (!$product) { continue; }
      $qty   = (float) ($row['qty'] ?? 1);
      $price = (float) ($row['price'] ?? $product['selling_price']);
      $base  = $qty * $price;

      $discount = 0.0; $promoId = null; $promoCode = null;
      if (!empty($row['promo_id'])) {
        $promoIdReq = (int)$row['promo_id'];
        // Validate: promo linked to product & this store and active
        $builder = $promoModel->builder();
        $valid = $builder->select('promos.*')
          ->join('promoitems','promoitems.promo_id = promos.id','inner')
          ->where('promos.id',$promoIdReq)
          ->where('promoitems.product_id',$product['id'])
          ->where('promos.store_id',$storeId)
          ->where('promos.start_datetime <=',$now)
          ->where('promos.end_datetime >=',$now)
          ->get()->getFirstRow('array');
        if ($valid) {
          if ($valid['type'] === 'percent') {
            $discount = $base * ((float)$valid['value'] / 100);
          } elseif ($valid['type'] === 'fixed') {
            $discount = (float)$valid['value'] * $qty;
          }
          $promoId = $valid['id'];
          $promoCode = $valid['promo_code'];
        }
      }

      $taxable = max(0, $base - $discount);
      if ($product['tax_included']) {
        $tax = $taxable * ((new \App\Config\Pos())->taxRate / (1 + (new \App\Config\Pos())->taxRate));
        $lineTotal = $taxable;
      } else {
        $tax = $taxable * (new \App\Config\Pos())->taxRate;
        $lineTotal = $taxable + $tax;
      }

      $totals['total_items'] += (int) round($qty);
      $totals['subtotal']    += $base;
      $totals['discount_total'] += $discount;
      $totals['tax_total']   += $tax;
      $totals['grand_total'] += $lineTotal;

      $lineRows[] = [
        'product_id' => $product['id'],
        'product_name' => $product['name'],
        'unit' => $product['unit'] ?? null,
        'qty' => $qty,
        'price' => $price,
        'discount' => round($discount,2),
        'tax' => round($tax,2),
        'line_total' => round($lineTotal,2),
        'applied_promo_id' => $promoId,
        'applied_promo_code' => $promoCode,
      ];
    }

    $paymentType = (string) ($payload['payment_type'] ?? ($payload['payment_method'] ?? ($payments[0]['method'] ?? 'cash')));
    $amountPaid  = (float) ($payload['amount_paid'] ?? ($payments[0]['amount'] ?? 0));
    $changeDue = max(0, $amountPaid - $totals['grand_total']);
    $status = $amountPaid >= $totals['grand_total'] ? 'paid' : ($amountPaid > 0 ? 'partial' : 'unpaid');

    $db = db_connect();
    $db->transBegin();
    try {
      $saleId = $saleModel->insert([
        'store_id'=>$storeId,'register_id'=>$registerId,'staff_id'=>$staffId,
        'receipt_no'=>$receiptNo,'sale_datetime'=>$now,
        'total_items'=>$totals['total_items'],'subtotal'=>round($totals['subtotal'],2),
        'discount_total'=>round($totals['discount_total'],2),'tax_total'=>round($totals['tax_total'],2),
        'grand_total'=>round($totals['grand_total'],2),'amount_paid'=>round($amountPaid,2),
        'change_due'=>round($changeDue,2),'status'=>$status,
        'payment'=>$paymentType,
      ], true);

      foreach ($lineRows as $lr) { $lr['sale_id'] = $saleId; $itemModel->insert($lr); }

      $db->transCommit();
    } catch (\Throwable $e) {
      $db->transRollback();
      return $this->respondOrRedirectError($e->getMessage());
    }

    if ($this->request->isAJAX()) { return $this->response->setJSON(['id'=>$saleId,'receipt_no'=>$receiptNo]); }
    return redirect()->to('/sales/'.$saleId)->with('success','Sale created');
  }

  public function show($id)
  {
    $sale = $this->saleModel->find($id);
    if (!$sale) {
      return $this->response->setStatusCode(404)->setJSON(['error' => 'Sale not found']);
    }
    $detail = [
      'sale' => $sale,
      'items' => $this->saleItemModel->where('sale_id', $id)->findAll(),
    ];
    if ($this->request->isAJAX()) {
      return $this->response->setJSON($detail);
    }
    $detail['title'] = 'Sale ' . $sale['receipt_no'];
    return view('sales/show', $detail);
  }

  public function delete($id)
  {
    $sale = $this->saleModel->find($id);
    if (!$sale) {
      return redirect()->to('/sales')->with('error', 'Sale not found');
    }

    $this->saleItemModel->where('sale_id', $id)->delete();
    $this->saleModel->delete($id);
    return redirect()->to('/sales')->with('success', 'Sale deleted');
  }

  private function respondOrRedirectError(string $message)
  {
    if ($this->request->isAJAX()) {
      return $this->response->setStatusCode(422)->setJSON(['error' => $message]);
    }
    return redirect()->back()->withInput()->with('errors', [$message]);
  }
}
