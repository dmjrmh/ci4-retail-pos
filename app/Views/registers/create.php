<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header d-flex align-items-center">
    <h3 class="card-title m-0"><?= esc($title ?? 'Add Register') ?></h3>
    <a href="<?= base_url('registers') ?>" class="btn btn-secondary btn-sm ml-2">Back</a>
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

    <form action="<?= base_url('registers/store') ?>" method="post">
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
        <label for="register_code">Register Code <span class="text-danger">*</span></label>
        <input type="text" id="register_code" name="register_code" class="form-control" value="<?= old('register_code') ?>">
      </div>

      <button type="submit" class="btn btn-primary">Save</button>
    </form>
  </div>
</div>

<?= $this->endSection() ?>