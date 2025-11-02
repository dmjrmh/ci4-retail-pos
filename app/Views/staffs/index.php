<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header d-flex align-items-center">
    <h3 class="card-title m-0">Staffs</h3>
    <a href="<?= base_url('staffs/create') ?>" class="btn btn-primary btn-sm ml-2">+ Add Staff</a>
  </div>

  <div class="card-body">
    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th class="text-center">#</th>
          <th class="text-center">Store</th>
          <th class="text-center">Name</th>
          <th class="text-center">Code</th>
          <th class="text-center">Position</th>
          <th class="text-center">Phone</th>
          <th class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (! empty($staffs)): ?>
          <?php foreach ($staffs as $index => $staff): ?>
            <tr>
              <td class="text-center"><?= $index + 1 ?></td>
              <td><?= esc($staff['store_name']) ?></td>
              <td><?= esc($staff['name']) ?></td>
              <td class="text-center"><?= esc($staff['staff_code']) ?></td>
              <td><?= esc($staff['position']) ?></td>
              <td><?= esc($staff['phone']) ?></td>
              <td class="text-center">
                <a href="<?= base_url('staffs/edit/' . $staff['id']) ?>" class="btn btn-warning btn-sm">
                  <i class="fas fa-edit"></i>
                </a>
                <form action="<?= base_url('staffs/delete/' . $staff['id']) ?>" method="post" class="d-inline"
                  onsubmit="return confirm('Are you sure to delete this staff?')">
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
            <td colspan="7" class="text-center">No staffs found</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>