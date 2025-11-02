<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header d-flex align-items-center px-3">
    <h3 class="card-title m-0"><?= $title ?></h3>
    <a href="<?= site_url('products/create') ?>" class="btn btn-primary btn-sm ml-2">
      + Add Product
    </a>
  </div>

  <div class="card-body">
    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
    <?php endif; ?>

    <table class="table table-bordered table-hover">
      <thead class="thead-dark">
        <tr>
          <th style="width: 5%" class="text-center">#</th>
          <th class="text-center">Name</th>
          <th class="text-center">Cover</th>
          <th class="text-center">Code</th>
          <th class="text-center">Unit</th>
          <th style="width: 15%" class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($products)): ?>
          <?php foreach ($products as $index => $product): ?>
            <tr>
              <td><?= $index + 1 ?></td>
              <td><?= esc($product['product_name']) ?></td>
              <td><?= esc($product['product_code']) ?></td>
              <td><?= esc($product['city']) ?></td>
              <td class="text-center">
                <a href="<?= base_url('products/edit/' . $product['id']) ?>" class="btn btn-warning btn-sm">
                  <i class="fas fa-edit"></i>
                </a>
                <form action="<?= base_url('products/delete/' . $product['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Are you sure to delete this product?')">
                  <?= csrf_field() ?>
                  <button class="btn btn-danger btn-sm" type="submit">
                    <i class="fas fa-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="6" class="text-center text-muted">No products found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>