<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header d-flex align-items-center">
    <h3 class="card-title m-0">Promos</h3>
    <a href="<?= base_url('promos/create') ?>" class="btn btn-primary btn-sm ml-2">+ Add Promo</a>
  </div>

  <div class="card-body">
    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th class="text-center" width="50">#</th>
          <th class="text-center">Promo Name</th>
          <th class="text-center">Promo Code</th>
          <th class="text-center">Store</th>
          <th class="text-center">Type</th>
          <th class="text-center">Value</th>
          <th class="text-center">Start Promo</th>
          <th class="text-center">End Promo</th>
          <th class="text-center" width="120">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (! empty($promos)): ?>
          <?php foreach ($promos as $index => $promo): ?>
            <tr>
              <td class="text-center"><?= $index + 1 ?></td>
              <td><?= esc($promo['name']) ?></td>
              <td><?= esc($promo['promo_code']) ?></td>
              <td class="text-center"><?= esc($promo['store_name'] ?? ('#'.$promo['store_id'])) ?></td>
              <td class="text-center"><?= ucfirst($promo['type']) ?></td>
              <td class="text-right">
                <?= $promo['type'] === 'percent'
                  ? esc($promo['value']) . '%'
                  : 'Rp ' . number_format($promo['value'], 0, ',', '.') ?>
              </td>
              <td class="text-center"><?= date('d M Y H:i', strtotime($promo['start_datetime'])) ?></td>
              <td class="text-center"><?= date('d M Y H:i', strtotime($promo['end_datetime'])) ?></td>
              <td class="text-center">
                <a href="<?= base_url('promos/edit/' . $promo['id']) ?>" class="btn btn-warning btn-sm">
                  <i class="fas fa-edit"></i>
                </a>
                <form action="<?= base_url('promos/delete/' . $promo['id']) ?>" method="post" class="d-inline"
                  onsubmit="return confirm('Are you sure to delete this promo?')">
                  <?= csrf_field() ?>
                  <button class="btn btn-danger btn-sm" type="submit">
                    <i class="fas fa-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="7" class="text-center">No promos found</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>
