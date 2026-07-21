<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - ' : '' ?>Keuangan App</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
<div class="app-wrapper">

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="fa-solid fa-wallet"></i> <span>Keuangan<b>App</b></span>
        </div>
        <nav class="sidebar-nav">
            <a href="<?= site_url('dashboard') ?>" class="nav-link <?= (uri_segment(1) == 'dashboard' || uri_segment(1) == '') ? 'active' : '' ?>">
                <i class="fa-solid fa-gauge"></i> <span>Dashboard</span>
            </a>
            <a href="<?= site_url('transaksi') ?>" class="nav-link <?= uri_segment(1) == 'transaksi' ? 'active' : '' ?>">
                <i class="fa-solid fa-right-left"></i> <span>Transaksi</span>
            </a>
            <a href="<?= site_url('kategori') ?>" class="nav-link <?= uri_segment(1) == 'kategori' ? 'active' : '' ?>">
                <i class="fa-solid fa-tags"></i> <span>Kategori</span>
            </a>
            <a href="<?= site_url('laporan') ?>" class="nav-link <?= uri_segment(1) == 'laporan' ? 'active' : '' ?>">
                <i class="fa-solid fa-file-invoice"></i> <span>Laporan Bulanan</span>
            </a>
            <div class="nav-divider"></div>
            <a href="<?= site_url('settings') ?>" class="nav-link <?= uri_segment(1) == 'settings' ? 'active' : '' ?>">
                <i class="fa-solid fa-gear"></i> <span>Settings</span>
            </a>
            <div class="nav-divider"></div>
            <a href="<?= site_url('logout') ?>" class="nav-link text-danger">
                <i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span>
            </a>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <!-- TOPBAR -->
        <header class="topbar">
            <button class="btn btn-sm btn-light d-lg-none" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
            <h5 class="topbar-title mb-0"><?= isset($title) ? $title : 'Dashboard' ?></h5>
            <div class="topbar-user">
                <i class="fa-solid fa-circle-user"></i>
                <span><?= isset($current_user) ? htmlspecialchars($current_user->name) : '' ?></span>
            </div>
        </header>

        <main class="page-content">
            <?php
                $success = $this->session->flashdata('success');
                $error   = $this->session->flashdata('error');
            ?>
            <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-circle-check"></i> <?= $success ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-triangle-exclamation"></i> <?= $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
