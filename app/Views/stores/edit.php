<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Edit Store</h3>
  </div>
  <form action="<?= base_url('stores') ?>/update/<?= $store['id'] ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="_method" value="PUT">
    <div class="card-body">
      <div class="form-group">
        <label>Store Name</label>
        <input type="text" name="store_name" class="form-control <?= session('errors.store_name') ? 'is-invalid' : '' ?>" value="<?= (old('store_name')) ? old('store_name') : $store['store_name'] ?>">
        <div class="invalid-feedback"><?= session('errors.store_name') ?></div>
      </div>
      <div class="form-group">
        <label>Store Code</label>
        <input type="text" name="store_code" class="form-control <?= session('errors.store_code') ? 'is-invalid' : '' ?>" value="<?= (old('store_code')) ? old('store_code') : $store['store_code'] ?>">
        <div class="invalid-feedback"><?= session('errors.store_code') ?></div>
      </div>
      <div class="form-group">
        <label>Address</label>
        <textarea name="address" class="form-control"><?= (old('address')) ? old('address') : $store['address'] ?></textarea>
      </div>
      <div class="form-group">
        <label>City</label>
        <input type="text" name="city" class="form-control" value="<?= (old('city')) ? old('city') : $store['city'] ?>">
      </div>
    </div>
    <div class="card-footer">
      <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Save</button>
      <a href="<?= base_url('stores') ?>" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>
<?= $this->endSection() ?>