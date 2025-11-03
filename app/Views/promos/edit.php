<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header d-flex align-items-center">
    <h3 class="card-title m-0">Edit Promo</h3>
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

    <form action="<?= base_url('promos/update/' . $promo['id']) ?>" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="_method" value="PUT">

      <div class="form-group">
        <label for="name">Promo Name <span class="text-danger">*</span></label>
        <input type="text" id="name" name="name" class="form-control" value="<?= old('name', $promo['name'] ?? '') ?>" autofocus>
      </div>

      <div class="form-group">
        <label for="promo_code">Promo code <span class="text-danger">*</span></label>
        <input type="text" id="promo_code" name="promo_code" class="form-control" value="<?= old('promo_code', $promo['promo_code'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label for="type">Type <span class="text-danger">*</span></label>
        <select id="type" name="type" class="form-control">
          <option value="percent" <?= old('type', $promo['type'] ?? '') === 'percent' ? 'selected' : '' ?>>Percent (%)</option>
          <option value="fixed" <?= old('type', $promo['type'] ?? '') === 'fixed'   ? 'selected' : '' ?>>Fixed (Rp)</option>
        </select>
      </div>

      <div class="form-group">
        <label for="value">Value <span class="text-danger">*</span></label>
        <input type="number" id="value" name="value" step="0.01" class="form-control" value="<?= old('value', $promo['value'] ?? '') ?>">
        <small class="text-muted">
          Jika Type = Percent, isi 5 untuk 5%. Jika Fixed, isi nominal rupiah (mis. 10000).
        </small>
      </div>

      <div class="form-group">
        <label for="start_datetime">Start Promo <span class="text-danger">*</span></label>
        <input type="datetime-local" id="start_datetime" name="start_datetime" class="form-control" value="<?= old('start_datetime', $promo['start_datetime'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label for="end_datetime">End Promo <span class="text-danger">*</span></label>
        <input type="datetime-local" id="end_datetime" name="end_datetime" class="form-control" value="<?= old('end_datetime', $promo['end_datetime'] ?? '') ?>">
      </div>

      <button type="submit" class="btn btn-primary">Update</button>
      <a href="<?= base_url('promos') ?>" class="btn btn-light">Cancel</a>
    </form>
  </div>
</div>

<?= $this->endSection() ?>