<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header d-flex align-items-center px-3">
    <h3 class="card-title m-0">Register List</h3>
    <a href="<?= site_url('registers/create') ?>" class="btn btn-primary btn-sm ml-2">
      + Add Register
    </a>
  </div>

  <div class="card-body">
    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
    <?php endif; ?>

    <table class="table table-bordered table-hover">
      <thead class="thead-dark">
        <tr>
          <th style="width: 5%" class="text-center">#</th>
          <th class="text-center">Register Name</th>
          <th class="text-center">Register Code</th>
          <th class="text-center">Store</th>
          <th style="width: 15%" class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($registers)): ?>
          <?php foreach ($registers as $index => $register): ?>
            <tr>
              <td class="text-center"><?= $index + 1 ?></td>
              <td><?= esc($register['name']) ?></td>
              <td class="font-monospace"><?= esc($register['register_code']) ?></td>
              <td>
                <?= esc($register['store_name'] ?? '-') ?>
                <?php if (!empty($register['store_code'])): ?>
                  <span class="text-muted">(<?= esc($register['store_code']) ?>)</span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <a href="<?= base_url('registers/edit/' . $register['id']) ?>" class="btn btn-warning btn-sm">
                  <i class="fas fa-edit"></i>
                </a>
                <form action="<?= base_url('registers/delete/' . $register['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Are you sure to delete this register?')">
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
            <td colspan="5" class="text-center text-muted">No registers found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>