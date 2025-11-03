<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header d-flex align-items-center">
    <h3 class="card-title m-0">Sales</h3>
    <a href="<?= base_url('sales/create') ?>" class="btn btn-primary btn-sm ml-2">+ New Sale</a>
  </div>
  <div class="card-body">
    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <table class="table table-bordered table-striped mb-2">
      <thead>
        <tr>
          <th class="text-center" width="50">#</th>
          <th>Receipt</th>
          <th>Store</th>
          <th>Register</th>
          <th>Date</th>
          <th class="text-right">Grand Total</th>
          <th>Payment Type</th>
          <th>Status</th>
          <th width="120" class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (! empty($sales)): ?>
          <?php foreach ($sales as $i => $s): ?>
            <tr>
              <td class="text-center"><?= $i + 1 ?></td>
              <td><?= esc($s['receipt_no']) ?></td>
              <td><?= esc($s['store_name'] ?? ('#' . $s['store_id'])) ?></td>
              <td><?= esc($s['register_name'] ?? ('#' . $s['register_id'])) ?></td>
              <td><?= esc($s['sale_datetime']) ?></td>
              <td class="text-right"><?= number_format($s['grand_total'], 2) ?></td>
              <td><?= esc(strtoupper($s['payment'] ?? '')) ?></td>
              <td><?= esc($s['status']) ?></td>
              <td class="text-center">
                <a href="<?= base_url('sales/' . $s['id']) ?>" class="btn btn-info btn-sm" title="View">
                  <i class="fas fa-eye"></i>
                </a>
                <form action="<?= base_url('sales/delete/' . $s['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Delete this sale?')">
                  <?= csrf_field() ?>
                  <button class="btn btn-danger btn-sm" type="submit" title="Delete">
                    <i class="fas fa-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="9" class="text-center">No sales yet</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
    <?= $pager ? $pager->links('default', 'user_pagination') : '' ?>
  </div>
</div>

<?= $this->endSection() ?>
