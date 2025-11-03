<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header d-flex align-items-center">
    <h3 class="card-title m-0">Receipt <?= esc($sale['receipt_no']) ?></h3>
    <a href="<?= base_url('sales') ?>" class="btn btn-default btn-sm ml-2">Back</a>
  </div>
  <div class="card-body">
    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <div class="row">
      <div class="col-md-6">
        <p><strong>Date:</strong> <?= esc($sale['sale_datetime']) ?></p>
        <p><strong>Status:</strong> <?= esc($sale['status']) ?></p>
      </div>
      <div class="col-md-6 text-right">
        <p><strong>Subtotal:</strong> <?= number_format($sale['subtotal'],2) ?></p>
        <p><strong>Discount:</strong> <?= number_format($sale['discount_total'],2) ?></p>
        <p><strong>Tax:</strong> <?= number_format($sale['tax_total'],2) ?></p>
        <p><strong>Grand Total:</strong> <?= number_format($sale['grand_total'],2) ?></p>
        <p><strong>Payment Type:</strong> <?= esc(strtoupper($sale['payment'] ?? 'CASH')) ?></p>
        <p><strong>Paid:</strong> <?= number_format($sale['amount_paid'],2) ?></p>
        <p><strong>Change:</strong> <?= number_format($sale['change_due'],2) ?></p>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>Product</th>
            <th class="text-right">Qty</th>
            <th class="text-right">Price</th>
            <th class="text-right">Discount</th>
            <th class="text-right">Tax</th>
            <th class="text-right">Total</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($items as $i => $it): ?>
            <tr>
              <td><?= $i+1 ?></td>
              <td><?= esc($it['product_name']) ?><?php if($it['applied_promo_code']): ?> <small class="text-success">(<?= esc($it['applied_promo_code']) ?>)</small><?php endif; ?></td>
              <td class="text-right"><?= number_format($it['qty'],2) ?></td>
              <td class="text-right"><?= number_format($it['price'],2) ?></td>
              <td class="text-right"><?= number_format($it['discount'],2) ?></td>
              <td class="text-right"><?= number_format($it['tax'],2) ?></td>
              <td class="text-right"><?= number_format($it['line_total'],2) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    
  </div>
</div>

<?= $this->endSection() ?>
