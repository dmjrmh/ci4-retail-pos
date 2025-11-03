<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-lg-3 col-6">
    <div class="small-box bg-info">
      <div class="inner">
        <h3><?= esc($counts['stores'] ?? 0) ?></h3>
        <p>Stores</p>
      </div>
      <div class="icon"><i class="fas fa-store"></i></div>
      <a href="<?= base_url('stores') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box bg-success">
      <div class="inner">
        <h3><?= esc($counts['staffs'] ?? 0) ?></h3>
        <p>Staffs</p>
      </div>
      <div class="icon"><i class="fas fa-user"></i></div>
      <a href="<?= base_url('staffs') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box bg-warning">
      <div class="inner">
        <h3><?= esc($counts['sales'] ?? 0) ?></h3>
        <p>Sales</p>
      </div>
      <div class="icon"><i class="fas fa-receipt"></i></div>
      <a href="<?= base_url('sales') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box bg-primary">
      <div class="inner">
        <h3><?= esc($counts['products'] ?? 0) ?></h3>
        <p>Products</p>
      </div>
      <div class="icon"><i class="fas fa-box"></i></div>
      <a href="<?= base_url('products') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box bg-danger">
      <div class="inner">
        <h3><?= esc($counts['promos'] ?? 0) ?></h3>
        <p>Promos</p>
      </div>
      <div class="icon"><i class="fas fa-tags"></i></div>
      <a href="<?= base_url('promos') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

