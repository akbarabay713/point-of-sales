<?= $this->extend('layout/main') ?>

<?= $this->section('menu') ?>

<?php if (session()->get('level') == 1) : ?>


    <li class="nav-item">
        <a href="<?= site_url('admin/index') ?>" class="nav-link">
            <i class="nav-icon fa fa-tachometer-alt"></i>
            <p>
                Dashboard
            </p>
        </a>
    </li>

    <li class="nav-item">
        <a href="<?= site_url('barang/index') ?>" class="nav-link">
            <i class="nav-icon fa fa-table"></i>
            <p>
                Barang
            </p>
        </a>
    </li>
    <li class="nav-item">
        <a href="<?= site_url('barang/stok') ?>" class="nav-link">
            <i class="nav-icon fa fa-table"></i>
            <p>
                Pembelian Barang
            </p>
        </a>
    </li>
    <li class="nav-item">
        <a href="<?= site_url('laporan') ?>" class="nav-link">
            <i class="nav-icon fa fa-table"></i>
            <p>
                Laporan
            </p>
        </a>
    </li>

    <li class="nav-item">
        <a href="<?= site_url('admin/user_management') ?>" class="nav-link">
            <i class="nav-icon fa fa-users"></i>
            <p>
                User Management
            </p>
        </a>
    </li>

    <li class="nav-item">
        <a href="<?= site_url('auth/logout') ?>" class="nav-link">
            <i class="nav-icon fa fa-sign-out-alt"></i>
            <p>
                Log Out
            </p>
        </a>
    </li>

<?php else : ?>
    <li class="nav-item">
        <a href="<?= site_url('admin/index') ?>" class="nav-link">
            <i class="nav-icon fa fa-tachometer-alt"></i>
            <p>
                Dashboard
            </p>
        </a>
    </li>
    <li class="nav-item">
        <a href="<?= site_url('penjualan/index') ?>" class="nav-link">
            <i class="nav-icon fa fa-table"></i>
            <p>
                Transaksi
            </p>
        </a>
    </li>


    <li class="nav-item">
        <a href="<?= site_url('auth/logout') ?>" class="nav-link">
            <i class="nav-icon fa fa-sign-out-alt"></i>
            <p>
                Log Out
            </p>
        </a>
    </li>
<?php endif; ?>

<?= $this->endSection(); ?>