<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header d-flex align-items-center">
    <h3 class="card-title m-0"><?= esc($title ?? 'Add Promo') ?></h3>
    <a href="<?= base_url('promos') ?>" class="btn btn-secondary btn-sm ml-2">Back</a>
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

    <form action="<?= base_url('promos/store') ?>" method="post">
      <?= csrf_field() ?>

      <div class="form-group">
        <label for="name">Name <span class="text-danger">*</span></label>
        <input type="text" id="name" name="name" class="form-control" value="<?= old('name') ?>">
      </div>
      
      <div class="form-group">
        <label for="promo_code">Promo code <span class="text-danger">*</span></label>
        <input type="text" id="promo_code" name="promo_code" class="form-control" value="<?= old('promo_code') ?>">
      </div>

      <div class="form-group">
        <label for="type">Type <span class="text-danger">*</span></label>
        <select id="type" name="type" class="form-control">
          <option value="percent" <?= old('type') === 'percent' ? 'selected' : '' ?>>Percent (%)</option>
          <option value="fixed" <?= old('type') === 'fixed' ? 'selected' : '' ?>>Fixed (Rp)</option>
        </select>
      </div>

      <div class="form-group">
        <label for="value">Value <span class="text-danger">*</span></label>
        <input type="number" step="0.01" id="value" name="value" class="form-control" value="<?= old('value') ?>">
        <small class="text-muted">Jika Type = Percent, isi 5 untuk 5%. Jika Fixed, isi nominal rupiah.</small>
      </div>

      <div class="form-group">
        <label for="start_datetime">Start Datetime <span class="text-danger">*</span></label>
        <input type="datetime-local" id="start_datetime" name="start_datetime" class="form-control" value="<?= old('start_datetime') ?>">
      </div>

      <div class="form-group">
        <label for="end_datetime">End Datetime <span class="text-danger">*</span></label>
        <input type="datetime-local" id="end_datetime" name="end_datetime" class="form-control" value="<?= old('end_datetime') ?>">
      </div>

      <button type="submit" class="btn btn-primary">Save</button>
      <a href="<?= base_url('promos') ?>" class="btn btn-light">Cancel</a>
    </form>
  </div>
</div>

<?= $this->endSection() ?>