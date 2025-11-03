<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header d-flex align-items-center">
    <h3 class="card-title m-0">Promo Items</h3>
  </div>

  <div class="card-body">
    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th class="text-center" width="50">#</th>
          <th class="text-center">Promo Name</th>
          <th class="text-center">Store</th>
          <th class="text-center">Product Name</th>
          <th class="text-center" width="120">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (! empty($promoitems)): ?>
          <?php foreach ($promoitems as $index => $promoitem): ?>
            <tr>
              <td class="text-center"><?= $index + 1 ?></td>
              <td><?= esc($promoitem['promo_name']) ?></td>
              <td class="text-center"><?= esc($promoitem['store_name'] ?? '-') ?></td>
              <td><?= esc($promoitem['product_name']) ?></td>
              <td class="text-center">
                <a href="<?= base_url('promos/edit/' . $promoitem['promo_id']) ?>" class="btn btn-warning btn-sm" title="Edit Promo & Items">
                  <i class="fas fa-edit"></i>
                </a>
                <form action="<?= base_url('promoitems/delete/' . $promoitem['id']) ?>" method="post" class="d-inline"
                  onsubmit="return confirm('Are you sure to delete this promo?')">
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
            <td colspan="7" class="text-center">No promoitems found</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>
