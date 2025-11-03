<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="<?= base_url() ?>" class="brand-link text-center">
    <span class="brand-text font-weight-light">Retail POS</span>
  </a>

  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
        <li class="nav-item">
          <a href="<?= base_url('sales') ?>" class="nav-link <?= is_active('sales') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-receipt"></i>
            <p>Sales</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url('stores') ?>" class="nav-link <?= is_active('stores') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-store"></i>
            <p>Stores</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url('products') ?>" class="nav-link  <?= is_active('products') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-box"></i>
            <p>Products</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url('staffs') ?>" class="nav-link  <?= is_active('staffs') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-user"></i>
            <p>Staffs</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url('registers') ?>" class="nav-link  <?= is_active('registers') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-cash-register"></i>
            <p>Registers</p>
          </a>
        </li>
      </ul>
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
        <li class="nav-item has-treeview <?= (is_active('promos') || is_active('promoitems')) ? 'menu-open' : '' ?>">
          <a href="#" class="nav-link <?= (is_active('promos') || is_active('promoitems')) ? 'active' : '' ?>">
            <i class="nav-icon fas fa-tags"></i>
            <p>
              Promos
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>

          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= base_url('promos') ?>" class="nav-link <?= is_active('promos') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Promo List</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('promoitems') ?>" class="nav-link <?= is_active('promoitems') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Promo Items</p>
              </a>
            </li>
            
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</aside>
