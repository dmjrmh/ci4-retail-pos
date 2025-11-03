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
        <label for="store_id">Store <span class="text-danger">*</span></label>
        <select id="store_id" name="store_id" class="form-control" required>
          <?php foreach(($stores ?? []) as $st): ?>
            <option value="<?= $st['id'] ?>" <?= old('store_id', $promo['store_id'] ?? '') == $st['id'] ? 'selected' : '' ?>><?= esc($st['store_name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

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

      <div class="table-responsive">
        <label>Products</label>
        <table class="table table-bordered" id="promo-products">
          <thead>
            <tr>
              <th width="50">#</th>
              <th>Product</th>
              <th width="60"></th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <button type="button" class="btn btn-secondary btn-sm" id="add-product-row">+ Add Product</button>
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

<script>
  const products = <?= json_encode($products ?? []) ?>;
  const selected = new Set(<?= json_encode($selected_product_ids ?? []) ?>);
  function makeProdRow(idx, selectedId) {
    const opts = products.map(p => `<option value='${p.id}' ${selectedId==p.id? 'selected':''}>${p.name}</option>`).join('');
    return `<tr>
      <td class="text-center">${idx+1}</td>
      <td><select name="product_id[]" class="form-control">${opts}</select></td>
      <td class="text-center"><button type="button" class="btn btn-danger btn-sm del-row">x</button></td>
    </tr>`;
  }
  function refreshIdx(){
    document.querySelectorAll('#promo-products tbody tr').forEach((tr,i)=>tr.children[0].textContent=i+1);
  }
  function hook(tr){ tr.querySelector('.del-row').addEventListener('click', ()=>{ tr.remove(); refreshIdx(); }); }
  function addRow(withId){
    const tbody = document.querySelector('#promo-products tbody');
    const wrap = document.createElement('tbody');
    wrap.innerHTML = makeProdRow(tbody.children.length, withId || '');
    const tr = wrap.firstElementChild; tbody.appendChild(tr); hook(tr);
  }
  // seed rows for selected, or one empty
  if (selected.size > 0) { selected.forEach(id => addRow(id)); } else { addRow(''); }
  document.getElementById('add-product-row').addEventListener('click', ()=> addRow(''));
</script>

<?= $this->endSection() ?>
