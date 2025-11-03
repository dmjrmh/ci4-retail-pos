<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header d-flex align-items-center">
    <h3 class="card-title m-0">Create Sale</h3>
    <a href="<?= base_url('sales') ?>" class="btn btn-default btn-sm ml-2">Back</a>
  </div>
  <form action="<?= base_url('sales/store') ?>" method="post">
    <?= csrf_field() ?>
    <div class="card-body">
      <?php if (session()->getFlashdata('errors')): $errs = (array) session()->getFlashdata('errors'); ?>
        <div class="alert alert-danger">
          <?= implode('<br>', array_map('esc', $errs)) ?>
        </div>
      <?php endif; ?>

      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label>Store</label>
            <select name="store_id" class="form-control" required>
              <?php foreach ($stores as $st): ?>
                <option value="<?= $st['id'] ?>"><?= esc($st['store_name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label>Register</label>
            <select name="register_id" class="form-control" required>
              <?php foreach ($registers as $rg): ?>
                <option value="<?= $rg['id'] ?>"><?= esc($rg['name']) ?> (<?= esc($rg['register_code']) ?>)</option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label>Staff</label>
            <select name="staff_id" class="form-control">
              <option value="">-- Choose Staff --</option>
              <?php foreach ($staffs as $sf): ?>
                <option value="<?= $sf['id'] ?>"><?= esc($sf['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>

      <hr>

        <div class="table-responsive">
          <table class="table table-bordered" id="items-table">
            <thead>
              <tr>
                <th width="40">#</th>
                <th>Product</th>
                <th width="220">Promo</th>
                <th width="100">Qty</th>
                <th width="140">Price</th>
                <th width="40"></th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
          <button type="button" class="btn btn-secondary btn-sm" id="add-item">+ Add Item</button>
        </div>

      <div class="row mt-3">
        <div class="col-md-4 ml-auto">
          <div class="form-group">
            <label>Payment Type</label>
            <select name="payment_type" class="form-control">
              <option value="cash">Cash</option>
              <option value="debit">Debit</option>
              <option value="qris">QRIS</option>
            </select>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label>Amount Paid</label>
            <input type="number" step="0.01" class="form-control" name="amount_paid" value="0">
          </div>
        </div>
      </div>

      <div class="row mt-2">
        <div class="col-md-6 ml-auto">
          <div class="border rounded p-3 bg-light">
            <div class="d-flex justify-content-between"><span>Subtotal</span><strong id="subtotal">0.00</strong></div>
            <div class="d-flex justify-content-between"><span>Discount</span><strong id="discount_total">0.00</strong></div>
            <div class="d-flex justify-content-between"><span>Tax</span><strong id="tax_total">0.00</strong></div>
            <div class="d-flex justify-content-between"><span>Grand Total</span><strong id="grand_total">0.00</strong></div>
            <hr class="my-2">
            <div class="d-flex justify-content-between"><span>Change</span><strong id="change_due">0.00</strong></div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-footer text-right">
      <button class="btn btn-primary" type="submit">Save</button>
    </div>
  </form>
</div>

<script>
  const products = <?= json_encode($products) ?>;
  const registersApi = '<?= base_url('api/registers/by-store') ?>';
  const staffsApi = '<?= base_url('api/staffs/by-store') ?>';
  const taxRate = <?= (new \App\Config\Pos())->taxRate ?>;
  function fmt(n){ return Number(n||0).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2}); }
  function setAmt(id, val){ const el = document.getElementById(id); if(el) el.textContent = fmt(val); }
  function calcSummary(){
    let subtotal=0, discountTotal=0, taxTotal=0, grandTotal=0, totalItems=0;
    document.querySelectorAll('#items-table tbody tr').forEach(tr => {
      const pid = parseInt((tr.querySelector('.product-select')?.value)||'0',10);
      const qty = parseFloat((tr.querySelector('input[name="qty[]"]')?.value)||'0');
      const price = parseFloat((tr.querySelector('input[name="price[]"]')?.value)||'0');
      const base = qty * price;
      totalItems += qty;
      let discount = 0;
      const psel = tr.querySelector('.promo-select');
      const opt = psel?.selectedOptions?.[0];
      if (opt && opt.value) {
        const t = opt.dataset.type; const v = parseFloat(opt.dataset.value||'0');
        if (t === 'percent') discount = base * (v/100);
        else if (t === 'fixed') discount = v * qty;
      }
      const prod = products.find(p=>p.id==pid) || {};
      const taxable = Math.max(0, base - discount);
      let tax=0, lineTotal=0;
      if (prod.tax_included) { tax = taxable * (taxRate/(1+taxRate)); lineTotal = taxable; }
      else { tax = taxable * taxRate; lineTotal = taxable + tax; }
      subtotal += base; discountTotal += discount; taxTotal += tax; grandTotal += lineTotal;
    });
    setAmt('subtotal', subtotal); setAmt('discount_total', discountTotal);
    setAmt('tax_total', taxTotal); setAmt('grand_total', grandTotal);
    const paid = parseFloat(document.querySelector('input[name="amount_paid"]').value||'0');
    setAmt('change_due', Math.max(0, paid - grandTotal));
  }
  function loadRegisters(storeId) {
    const regSel = document.querySelector('select[name="register_id"]');
    if (!regSel || !storeId) return;
    regSel.innerHTML = '<option>Loading...</option>';
    fetch(`${registersApi}?store_id=${storeId}`)
      .then(r => r.json())
      .then(res => {
        const list = (res && res.data) ? res.data : [];
        const options = list.map(r => `<option value="${r.id}">${r.name} (${r.register_code})</option>`).join('');
        regSel.innerHTML = options || '<option value="">-- No Register --</option>';
      })
      .catch(() => { regSel.innerHTML = '<option value="">-- No Register --</option>'; });
  }
  function loadStaffs(storeId){
    const staffSel = document.querySelector('select[name="staff_id"]');
    if (!staffSel) return;
    staffSel.innerHTML = '<option>Loading...</option>';
    if (!storeId) { staffSel.innerHTML = '<option value="">-- Choose Staff --</option>'; return; }
    fetch(`${staffsApi}?store_id=${storeId}`)
      .then(r => r.json())
      .then(res => {
        const list = (res && res.data) ? res.data : [];
        const options = ['<option value="">-- Choose Staff --</option>']
          .concat(list.map(s => `<option value="${s.id}">${s.name}</option>`))
          .join('');
        staffSel.innerHTML = options;
      })
      .catch(() => { staffSel.innerHTML = '<option value="">-- Choose Staff --</option>'; });
  }
  function makeRow(idx) {
    const opts = products.map(p => `<option value="${p.id}" data-price="${p.selling_price}">${p.name}</option>`).join('');
    return `<tr>
      <td class="text-center align-middle">${idx+1}</td>
      <td><select name="product_id[]" class="form-control product-select">${opts}</select></td>
      <td><select name="promo_id[]" class="form-control promo-select"><option value="">-- No Promo --</option></select></td>
      <td><input type="number" step="1" name="qty[]" value="1" class="form-control"></td>
      <td><input type="number" step="1" name="price[]" value="0" class="form-control price-input"></td>
      <td class="text-center align-middle"><button type="button" class="btn btn-danger btn-sm del-row">x</button></td>
    </tr>`;
  }
  function refreshIndexes(){
    const rows = document.querySelectorAll('#items-table tbody tr');
    rows.forEach((tr, i) => tr.querySelector('td').textContent = i+1);
  }
  function hookRow(tr){
    const select = tr.querySelector('.product-select');
    const price = tr.querySelector('.price-input');
    const promoSel = tr.querySelector('.promo-select');
    select.addEventListener('change', e => {
      const opt = select.selectedOptions[0];
      if (opt && opt.dataset.price) price.value = opt.dataset.price;
      // fetch eligible promos for selected product + current store
      const storeSel = document.querySelector('select[name="store_id"]');
      const storeId = storeSel ? storeSel.value : '';
      promoSel.innerHTML = '<option>Loading...</option>';
      if (opt && storeId) {
        fetch(`<?= base_url('api/promos/eligible') ?>?product_id=${opt.value}&store_id=${storeId}`)
          .then(r => r.json())
          .then(res => {
            const list = (res && res.data) ? res.data : [];
            const options = ['<option value="">-- No Promo --</option>']
              .concat(list.map(p => `<option value="${p.id}" data-type="${p.type}" data-value="${p.value}">${p.promo_code} - ${p.name}</option>`))
              .join('');
            promoSel.innerHTML = options;
            calcSummary();
          })
          .catch(() => { promoSel.innerHTML = '<option value="">-- No Promo --</option>'; calcSummary(); });
      } else {
        promoSel.innerHTML = '<option value="">-- No Promo --</option>';
      }
    });
    promoSel.addEventListener('change', calcSummary);
    tr.querySelector('input[name="qty[]"]').addEventListener('input', calcSummary);
    price.addEventListener('input', calcSummary);
    tr.querySelector('.del-row').addEventListener('click', () => {
      tr.remove();
      refreshIndexes();
      calcSummary();
    });
    // init price
    select.dispatchEvent(new Event('change'));
  }
  document.getElementById('add-item').addEventListener('click', () => {
    const tbody = document.querySelector('#items-table tbody');
    const idx = tbody.children.length;
    const wrapper = document.createElement('tbody');
    wrapper.innerHTML = makeRow(idx);
    const tr = wrapper.firstElementChild;
    tbody.appendChild(tr);
    hookRow(tr);
    calcSummary();
  });
  // when store changes, refresh promos on each row
  document.querySelector('select[name="store_id"]').addEventListener('change', (e) => {
    // reload registers list for selected store
    loadRegisters(e.target.value);
    loadStaffs(e.target.value);
    document.querySelectorAll('#items-table tbody tr').forEach(tr => {
      const sel = tr.querySelector('.product-select');
      if (sel) sel.dispatchEvent(new Event('change'));
    });
  });

  // add initial row
  document.getElementById('add-item').click();
  // init registers for default selected store
  (function initRegisters(){
    const storeSel = document.querySelector('select[name="store_id"]');
    if (storeSel && storeSel.value) { loadRegisters(storeSel.value); loadStaffs(storeSel.value); }
  })();
  document.querySelector('input[name="amount_paid"]').addEventListener('input', calcSummary);
  // initial totals
  calcSummary();
</script>

<?= $this->endSection() ?>
