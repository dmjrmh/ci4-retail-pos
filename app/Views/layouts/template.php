<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title><?= esc($title ?? 'Dashboard') ?></title>
  <link rel="stylesheet" href="<?= base_url('adminlte/plugins/fontawesome-free/css/all.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('adminlte/dist/css/adminlte.min.css') ?>">
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <?= $this->include('layouts/navbar') ?>
    <?= $this->include('layouts/sidebar') ?>

    <div class="content-wrapper">
      <section class="content pt-3">
        <div class="container-fluid">
          <?= $this->renderSection('content') ?>
        </div>
      </section>
    </div>
  </div>

  <script src="<?= base_url('adminlte/plugins/jquery/jquery.min.js') ?>"></script>
  <script src="<?= base_url('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <script src="<?= base_url('adminlte/dist/js/adminlte.min.js') ?>"></script>
</body>

</html>