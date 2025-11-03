<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Laporan Penjualan per Store & Sales</h3>
  </div>
  <div class="card-body">
    <form method="get" action="<?= base_url('reports') ?>" class="row mb-3">
      <div class="col-md-2">
        <label class="form-label">Dari</label>
        <input type="date" name="from" value="<?= esc($from) ?>" class="form-control">
      </div>
      <div class="col-md-2">
        <label class="form-label">Sampai</label>
        <input type="date" name="to" value="<?= esc($to) ?>" class="form-control">
      </div>
      <div class="col-md-3">
        <label class="form-label">Store</label>
        <select name="store_id" class="form-control">
          <option value="">Semua</option>
          <?php foreach ($stores as $s): ?>
            <option value="<?= $s['id'] ?>" <?= ($store_id == $s['id']) ? 'selected' : '' ?>><?= esc($s['store_name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Sales</label>
        <select name="staff_id" class="form-control">
          <option value="">Semua</option>
          <?php foreach ($salespeople as $u): ?>
            <option value="<?= $u['id'] ?>" <?= ($staff_id == $u['id']) ? 'selected' : '' ?>><?= esc($u['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2 d-flex align-items-end">
        <button class="btn btn-primary">Tampilkan</button>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-sm table-bordered">
        <thead class="table-light">
          <tr>
            <th>Store</th>
            <th>Sales</th>
            <th class="text-end">Transaksi</th>
            <th class="text-end">Qty</th>
            <th class="text-end">Subtotal</th>
            <th class="text-end">Diskon</th>
            <th class="text-end">Net</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><?= esc($r['store']) ?></td>
              <td><?= esc($r['salesperson']) ?></td>
              <td class="text-end"><?= number_format((int) $r['total_tx']) ?></td>
              <td class="text-end"><?= number_format((float) $r['total_qty'], 2) ?></td>
              <td class="text-end"><?= number_format((float) $r['subtotal'], 2) ?></td>
              <td class="text-end"><?= number_format((float) $r['discount'], 2) ?></td>
              <td class="text-end"><?= number_format((float) $r['net'], 2) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <?php
        $gtx = array_sum(array_map(fn($x) => (int)$x['total_tx'], $rows));
        $gqty = array_sum(array_map(fn($x) => (float)$x['total_qty'], $rows));
        $gsub = array_sum(array_map(fn($x) => (float)$x['subtotal'], $rows));
        $gdisc = array_sum(array_map(fn($x) => (float)$x['discount'], $rows));
        $gnet = array_sum(array_map(fn($x) => (float)$x['net'], $rows));
        ?>
        <tfoot>
          <tr>
            <th colspan="2">Grand Total</th>
            <th class="text-end"><?= number_format($gtx) ?></th>
            <th class="text-end"><?= number_format($gqty, 2) ?></th>
            <th class="text-end"><?= number_format($gsub, 2) ?></th>
            <th class="text-end"><?= number_format($gdisc, 2) ?></th>
            <th class="text-end"><?= number_format($gnet, 2) ?></th>
          </tr>
        </tfoot>
      </table>
    </div>

    <hr>
    <h5>Detail Transaksi & Item</h5>
    <?php
    $groupKey = function ($store, $sales) {
      return $store . '|' . $sales;
    };
    ?>
    <?php foreach ($rows as $r): ?>
      <?php $gk = $groupKey($r['store'], $r['salesperson']);
      $salesInGroup = $detailsByGroup[$gk] ?? []; ?>
      <div class="mt-3">
        <div class="fw-bold mb-2">Store: <?= esc($r['store']) ?> &mdash; Sales: <?= esc($r['salesperson']) ?></div>
        <?php if (empty($salesInGroup)): ?>
          <div class="text-muted">Tidak ada transaksi pada periode ini.</div>
        <?php else: ?>
          <?php foreach ($salesInGroup as $sale): ?>
            <div class="mb-2">
              <div class="small text-secondary">No: <?= esc($sale['receipt_no']) ?> | Tanggal: <?= esc($sale['sale_datetime']) ?></div>
              <div class="table-responsive">
                <table class="table table-sm table-striped">
                  <thead>
                    <tr>
                      <th>Item</th>
                      <th class="text-end">Qty</th>
                      <th class="text-end">Harga</th>
                      <th class="text-end">Diskon</th>
                      <th class="text-end">Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($sale['items'] as $it): ?>
                      <tr>
                        <td><?= esc($it['product_name']) ?></td>
                        <td class="text-end"><?= number_format((float) $it['qty'], 2) ?></td>
                        <td class="text-end"><?= number_format((float) $it['price'], 2) ?></td>
                        <td class="text-end"><?= number_format((float) $it['discount'], 2) ?></td>
                        <td class="text-end"><?= number_format((float) $it['line_total'], 2) ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <hr>
    <?php endforeach; ?>
  </div>
</div>

<?= $this->endSection() ?>