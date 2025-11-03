<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header d-flex align-items-center">
    <h3 class="card-title m-0">Promo Stores</h3>
    <a href="<?= base_url('promostores/create') ?>" class="btn btn-primary btn-sm ml-2">+ Add Promo Store</a>
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
          <th class="text-center">Store</th>
          <th class="text-center" width="120">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (! empty($promostores)): ?>
          <?php foreach ($promostores as $index => $row): ?>
            <tr>
              <td class="text-center"><?= $index + 1 ?></td>
              <td><?= esc($row['promo_name']) ?></td>
              <td><?= esc($row['store_name']) ?></td>
              <td class="text-center">
                <a href="<?= base_url('promostores/edit/' . $row['id']) ?>" class="btn btn-warning btn-sm">
                  <i class="fas fa-edit"></i>
                </a>
                <form action="<?= base_url('promostores/delete/' . $row['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Delete this item?')">
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
            <td colspan="7" class="text-center">No data found</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>
