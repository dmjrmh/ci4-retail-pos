<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header d-flex align-items-center">
    <h3 class="card-title m-0"><?= esc($title ?? 'Edit Promo Store') ?></h3>
    <a href="<?= site_url('promostores') ?>" class="btn btn-secondary btn-sm ml-2">Back</a>
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

    <form action="<?= site_url('promostores/update/' . $promostore['id']) ?>" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="_method" value="PUT">

      <div class="form-group">
        <label for="promo_id">Promo <span class="text-danger">*</span></label>
        <select id="promo_id" name="promo_id" class="form-control">
          <option value="">-- Select Promo --</option>
          <?php $selectedPromo = old('promo_id', $promostore['promo_id']); ?>
          <?php foreach ($promos as $promo): ?>
            <option value="<?= $promo['id'] ?>" <?= $selectedPromo == $promo['id'] ? 'selected' : '' ?>>
              <?= esc($promo['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="store_id">Store <span class="text-danger">*</span></label>
        <select id="store_id" name="store_id" class="form-control">
          <option value="">-- Select Store --</option>
          <?php $selectedStore = old('store_id', $promostore['store_id']); ?>
          <?php foreach ($stores as $store): ?>
            <option value="<?= $store['id'] ?>" <?= $selectedStore == $store['id'] ? 'selected' : '' ?>>
              <?= esc($store['store_name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <button type="submit" class="btn btn-primary">Update</button>
    </form>
  </div>
  
</div>

<?= $this->endSection() ?>
