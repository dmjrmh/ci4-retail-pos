<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header d-flex align-items-center">
    <h3 class="card-title m-0"><?= esc($title ?? 'Edit Register') ?></h3>
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

    <form action="<?= base_url('registers/update/' . $register['id']) ?>" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="_method" value="PUT">

      <div class="form-group">
        <label for="store_id">Store <span class="text-danger">*</span></label>
        <select id="store_id" name="store_id" class="form-control">
          <option value="">-- Select Store --</option>
          <?php foreach ($stores as $store): ?>
            <option value="<?= esc($store['id']) ?>"
              <?= old('store_id', $register['store_id'] ?? null) == $store['id'] ? 'selected' : '' ?>>
              <?= esc($store['store_name']) ?> (<?= esc($store['store_code'] ?? '') ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="name">Name <span class="text-danger">*</span></label>
        <input
          type="text"
          id="name"
          name="name"
          class="form-control"
          value="<?= esc(old('name', $register['name'] ?? '')) ?>">
      </div>

      <div class="form-group">
        <label for="register_code">Register Code <span class="text-danger">*</span></label>
        <input
          type="text"
          id="register_code"
          name="register_code"
          class="form-control"
          value="<?= esc(old('register_code', $register['register_code'] ?? '')) ?>">
      </div>

      <button type="submit" class="btn btn-primary">Update</button>
    </form>
  </div>
</div>

<?= $this->endSection() ?>