<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header d-flex align-items-center">
    <h3 class="card-title m-0"><?= esc($title ?? 'Edit Promo Item') ?></h3>
    <a href="<?= site_url('promoitems') ?>" class="btn btn-secondary btn-sm ml-2">Back</a>
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

    <form action="<?= site_url('promoitems/update/' . $promoitem['id']) ?>" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="_method" value="PUT">

      <div class="form-group">
        <label for="promo_id">Promo <span class="text-danger">*</span></label>
        <select id="promo_id" name="promo_id" class="form-control">
          <option value="">-- Select Promo --</option>
          <?php $selectedPromo = old('promo_id', $promoitem['promo_id']); ?>
          <?php foreach ($promos as $promo): ?>
            <option value="<?= $promo['id'] ?>" <?= $selectedPromo == $promo['id'] ? 'selected' : '' ?>>
              <?= esc($promo['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="product_id">Product <span class="text-danger">*</span></label>
        <select id="product_id" name="product_id" class="form-control">
          <option value="">-- Select Product --</option>
          <?php $selectedProduct = old('product_id', $promoitem['product_id']); ?>
          <?php foreach ($products as $product): ?>
            <option value="<?= $product['id'] ?>" <?= $selectedProduct == $product['id'] ? 'selected' : '' ?>>
              <?= esc($product['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <button type="submit" class="btn btn-primary">Update</button>
    </form>
  </div>
  
</div>

<?= $this->endSection() ?>
