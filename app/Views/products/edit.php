<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header d-flex align-items-center justify-content-between">
    <h3 class="card-title m-0"><?= esc($title ?? 'Edit Product') ?></h3>
    <a href="<?= base_url('products') ?>" class="btn btn-secondary btn-sm">Back</a>
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

    <form action="<?= base_url('products/update/' . $product['id']) ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="_method" value="PUT">

      <div class="form-group">
        <label for="name">Name <span class="text-danger">*</span></label>
        <input
          type="text"
          id="name"
          name="name"
          class="form-control <?= $validation->hasError('name') ? 'is-invalid' : '' ?>"
          value="<?= esc(old('name', $product['name'])) ?>"
          placeholder="Input product name">
        <div class="invalid-feedback">
          <?= $validation->getError('name') ?>
        </div>
      </div>

      <div class="form-group">
        <label for="unit">Unit <span class="text-danger">*</span></label>
        <select
          id="unit"
          name="unit"
          class="form-control <?= $validation->hasError('unit') ? 'is-invalid' : '' ?>">
          <?php
          $units = ['pcs', 'box', 'kg', 'gram', 'liter', 'pack', 'botol'];
          $selected = old('unit', $product['unit'] ?? '');
          ?>
          <option value="" disabled <?= $selected ? '' : 'selected' ?>>-- choose unit --</option>
          <?php foreach ($units as $u): ?>
            <option value="<?= esc($u) ?>" <?= $selected === $u ? 'selected' : '' ?>>
              <?= strtoupper($u) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <div class="invalid-feedback">
          <?= $validation->getError('unit') ?>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="cost_price">Cost Price</label>
          <input
            type="number"
            step="0.01"
            id="cost_price"
            name="cost_price"
            class="form-control <?= $validation->hasError('cost_price') ? 'is-invalid' : '' ?>"
            value="<?= esc(old('cost_price', $product['cost_price'])) ?>"
            placeholder="0.00">
          <div class="invalid-feedback">
            <?= $validation->getError('cost_price') ?>
          </div>
        </div>

        <div class="form-group col-md-6">
          <label for="selling_price">Selling Price</label>
          <input
            type="number"
            step="0.01"
            id="selling_price"
            name="selling_price"
            class="form-control <?= $validation->hasError('selling_price') ? 'is-invalid' : '' ?>"
            value="<?= esc(old('selling_price', $product['selling_price'])) ?>"
            placeholder="0.00">
          <div class="invalid-feedback">
            <?= $validation->getError('selling_price') ?>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label for="cover">Cover (jpg/jpeg/png, max 2MB)</label>
        <div class="d-flex align-items-start gap-3">
          <div class="flex-grow-1">
            <input
              type="file"
              id="cover"
              name="cover"
              accept="image/*"
              class="form-control-file <?= $validation->hasError('cover') ? 'is-invalid' : '' ?>">
            <div class="invalid-feedback d-block">
              <?= $validation->getError('cover') ?>
            </div>
          </div>
        </div>

        <?php
        // Adjust the path below to match where you store uploaded covers.
        $currentCover = !empty($product['cover'])
          ? base_url('uploads/products/' . $product['cover'])
          : base_url('images/missing-cover.png');
        ?>
        <div class="mt-2">
          <img
            id="coverPreview"
            src="<?= esc($currentCover) ?>"
            alt="Cover preview"
            style="width:120px;height:120px;object-fit:cover;border-radius:8px;border:1px solid #ddd;">
        </div>
      </div>

      <div class="mt-4">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="<?= base_url('products') ?>" class="btn btn-light">Cancel</a>
      </div>
    </form>
  </div>
</div>

<script>
  (function() {
    const input = document.getElementById('cover');
    const preview = document.getElementById('coverPreview');
    const placeholder = '<?= base_url('images/missing-cover.png') ?>';

    input.addEventListener('change', function() {
      const file = this.files && this.files[0] ? this.files[0] : null;
      if (!file) {
        // keep current image; if you prefer to reset, set to placeholder:
        // preview.src = placeholder;
        return;
      }
      const url = URL.createObjectURL(file);
      preview.src = url;
    });
  })();
</script>

<?= $this->endSection() ?>