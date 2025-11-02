<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header d-flex align-items-center">
    <h3 class="card-title m-0"><?= esc($title ?? 'Edit Staff') ?></h3>
    <a href="<?= site_url('staffs') ?>" class="btn btn-secondary btn-sm ml-2">Back</a>
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

    <form action="<?= site_url('staffs/update/' . $staff['id']) ?>" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="_method" value="PUT"><!-- kalau routes pakai POST spoofing -->

      <div class="form-group">
        <label for="store_id">Store <span class="text-danger">*</span></label>
        <select id="store_id" name="store_id" class="form-control">
          <option value="">-- Select Store --</option>
          <?php
          $selectedStore = old('store_id', $staff['store_id']);
          foreach ($stores as $store):
          ?>
            <option value="<?= $store['id'] ?>" <?= $selectedStore == $store['id'] ? 'selected' : '' ?>>
              <?= esc($store['store_name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="staff_code">Staff Code <span class="text-danger">*</span></label>
        <input type="text" id="staff_code" name="staff_code" class="form-control"
          value="<?= old('staff_code', $staff['staff_code']) ?>">
      </div>

      <div class="form-group">
        <label for="name">Name <span class="text-danger">*</span></label>
        <input type="text" id="name" name="name" class="form-control"
          value="<?= old('name', $staff['name']) ?>">
      </div>

      <div class="form-group">
        <label for="position">Position <span class="text-danger">*</span></label>
        <input type="text" id="position" name="position" class="form-control"
          value="<?= old('position', $staff['position']) ?>">
      </div>

      <div class="form-group">
        <label for="phone">Phone</label>
        <input type="text" id="phone" name="phone" class="form-control"
          value="<?= old('phone', $staff['phone']) ?>">
      </div>

      <button type="submit" class="btn btn-primary">Update</button>
    </form>
  </div>
</div>

<?= $this->endSection() ?>