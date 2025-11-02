<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header d-flex align-items-center">
    <h3 class="card-title m-0"><?= esc($title ?? 'Add Staff') ?></h3>
    <a href="<?= base_url('staffs') ?>" class="btn btn-secondary btn-sm ml-2">Back</a>
  </div>

  <div class="card-body">
    <?php if ($errors = session()->get('errors')): ?>
      <div class="alert alert-danger">
        <ul class="mb-0">
          <?php foreach ($errors as $e): ?>
            <li><?= esc($e) ?></li>
          <?php endforeach ?>
        </ul>
      </div>
    <?php endif; ?>

    <form action="<?= base_url('staffs/store') ?>" method="post">
      <?= csrf_field() ?>

      <div class="form-group">
        <label for="store_id">Store <span class="text-danger">*</span></label>
        <select id="store_id" name="store_id" class="form-control">
          <option value="">-- Select Store --</option>
          <?php foreach ($stores as $store): ?>
            <option value="<?= $store['id'] ?>" <?= old('store_id') == $store['id'] ? 'selected' : '' ?>>
              <?= esc($store['store_name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="name">Name <span class="text-danger">*</span></label>
        <input type="text" id="name" name="name" class="form-control" value="<?= old('name') ?>">
      </div>

      <div class="form-group">
        <label for="staff_code">Staff Code <span class="text-danger">*</span></label>
        <input type="text" id="staff_code" name="staff_code" class="form-control" value="<?= old('staff_code') ?>">
      </div>

      <div class="form-group">
        <label for="position">Position <span class="text-danger">*</span></label>
        <input type="text" id="position" name="position" class="form-control" value="<?= old('position') ?>">
      </div>

      <div class="form-group">
        <label for="phone">Phone</label>
        <input type="text" id="phone" name="phone" class="form-control" value="<?= old('phone') ?>">
      </div>

      <button type="submit" class="btn btn-primary">Save</button>
    </form>
  </div>
</div>

<?= $this->endSection() ?>