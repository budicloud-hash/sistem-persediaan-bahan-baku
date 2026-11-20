<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Simulasi sesi pengguna aktif (dapat diganti via dropdown role di sidebar)
if (isset($_POST['switch_user'])) {
    $usersMap = [
        1 => ['idUser' => 1, 'username' => 'budi', 'role' => 'Admin Gudang'],
        2 => ['idUser' => 2, 'username' => 'daffa', 'role' => 'Bagian Produksi'],
        3 => ['idUser' => 3, 'username' => 'sultan', 'role' => 'Manajer Gudang'],
    ];
    $uid = (int)$_POST['switch_user'];
    if (isset($usersMap[$uid])) {
        $_SESSION['user'] = $usersMap[$uid];
    }
}
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = ['idUser' => 1, 'username' => 'budi', 'role' => 'Admin Gudang'];
}
$activeUser = $_SESSION['user'];
$activePage = $activePage ?? 'dashboard';
$baseUrl = $baseUrl ?? '.';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GUDANGKU — Sistem Persediaan Bahan Baku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= $baseUrl ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <aside class="col-md-3 col-lg-2 sidebar p-3 d-flex flex-column justify-content-between">
            <div>
                <div class="brand-box mb-4">
                    <div class="fw-bold fs-5"><i class="bi bi-box-seam me-2"></i>GUDANGKU</div>
                    <small class="opacity-75">Raw Material Inventory</small>
                </div>
                <div class="text-uppercase text-muted small fw-semibold mb-2 px-2">Menu Utama</div>
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?= $activePage === 'dashboard' ? 'active' : '' ?>" href="<?= $baseUrl ?>/index.php">
                            <i class="bi bi-grid me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $activePage === 'bahan_baku' ? 'active' : '' ?>" href="<?= $baseUrl ?>/views/bahan_baku/index.php">
                            <i class="bi bi-archive me-2"></i>Master Bahan Baku
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $activePage === 'material_keluar' ? 'active' : '' ?>" href="<?= $baseUrl ?>/views/material_keluar/index.php">
                            <i class="bi bi-box-arrow-up-right me-2"></i>Transaksi Keluar
                        </a>
                    </li>
                </ul>
            </div>

            <div class="border-top pt-3 mt-4">
                <div class="small text-muted mb-1">Pengguna Aktif:</div>
                <div class="fw-semibold"><?= htmlspecialchars($activeUser['username']) ?></div>
                <span class="badge bg-primary-subtle text-primary mb-2"><?= htmlspecialchars($activeUser['role']) ?></span>
                <form method="POST" class="mt-1">
                    <select name="switch_user" class="form-select form-select-sm" onchange="this.form.submit()" title="Ganti Peran Uji">
                        <option value="1" <?= $activeUser['idUser'] == 1 ? 'selected' : '' ?>>budi (Admin Gudang)</option>
                        <option value="2" <?= $activeUser['idUser'] == 2 ? 'selected' : '' ?>>daffa (Bagian Produksi)</option>
                        <option value="3" <?= $activeUser['idUser'] == 3 ? 'selected' : '' ?>>sultan (Manajer Gudang)</option>
                    </select>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="col-md-9 col-lg-10 p-4">
