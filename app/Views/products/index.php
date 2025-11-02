<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header d-flex align-items-center px-3">
    <h3 class="card-title m-0"><?= esc($title ?? 'Products') ?></h3>
    <a href="<?= base_url('products/create') ?>" class="btn btn-primary btn-sm ml-2">
      + Add Product
    </a>
  </div>

  <div class="card-body">
    <?php if ($msg = session()->getFlashdata('success')): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= esc($msg) ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
    <?php endif; ?>

    <table class="table table-bordered table-hover">
      <thead class="thead-dark">
        <tr>
          <th style="width: 5%" class="text-center">#</th>
          <th style="width: 12%" class="text-center">Name</th>
          <th style="width: 12%" class="text-center">Cover</th>
          <th style="width: 10%" class="text-center">Unit</th>
          <th style="width: 15%" class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($products)): ?>
          <?php
          $start = isset($pager) && method_exists($pager, 'getCurrentPage') && isset($perPage)
            ? ($pager->getCurrentPage() - 1) * $perPage
            : 0;
          ?>
          <?php foreach ($products as $i => $product): ?>
            <tr>
              <td class="text-center align-middle"><?= $start + $i + 1 ?></td>
              <td class="align-middle"><?= esc($product['name'] ?? $product['product_name'] ?? '-') ?></td>
              <td class="text-center align-middle">
                <?php
                $cover = $product['cover'] ?? null;
                $src = $cover ? base_url('images/' . $cover) : base_url('images/missing-cover.png');
                ?>
                <img src="<?= $src ?>" alt="<?= esc($product['name'] ?? 'Cover') ?>"
                  style="width:60px;height:60px;object-fit:cover;border-radius:6px;">
              </td>
              <td class="text-center align-middle"><?= esc($product['unit'] ?? '-') ?></td>
              <td class="text-center align-middle">
                <a href="<?= base_url('products/edit/' . $product['id']) ?>" class="btn btn-warning btn-sm">
                  <i class="fas fa-edit"></i>
                </a>
                <form action="<?= base_url('products/delete/' . $product['id']) ?>" method="post" class="d-inline"
                  onsubmit="return confirm('Are you sure to delete this product?')">
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

    <?php if (isset($pager)): ?>
      <div class="mt-3">
        <?= $pager->links() ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<?= $this->endSection() ?>