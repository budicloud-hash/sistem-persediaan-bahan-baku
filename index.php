<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/BahanBaku.php';
require_once __DIR__ . '/models/MaterialKeluar.php';

$db = (new Database())->getConnection();
$bahanModel = new BahanBaku($db);
$keluarModel = new MaterialKeluar($db);

$allBahan = $bahanModel->getAll();
$lowStock = $bahanModel->getLowStockItems();
$riwayatKeluar = $keluarModel->getLapKeluar();

$activePage = 'dashboard';
$baseUrl = '.';
include __DIR__ . '/views/layout/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Dashboard Persediaan Bahan Baku</h3>
        <p class="text-muted mb-0">Ringkasan stok real-time & integrasi modul Master Data dengan Transaksi Keluar.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="views/bahan_baku/index.php" class="btn btn-outline-primary">
            <i class="bi bi-plus-circle me-1"></i>Kelola Master Bahan
        </a>
        <a href="views/material_keluar/index.php" class="btn btn-primary-navy">
            <i class="bi bi-box-arrow-up-right me-1"></i>Input Transaksi Keluar
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card card-clean p-3">
            <div class="text-muted small">Total Jenis Bahan Baku</div>
            <div class="fs-2 fw-bold text-primary"><?= count($allBahan) ?> <span class="fs-6 fw-normal text-muted">Item</span></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-clean p-3">
            <div class="text-muted small">Low-Stock Alert (&le; Safety Stock)</div>
            <div class="fs-2 fw-bold text-danger"><?= count($lowStock) ?> <span class="fs-6 fw-normal text-muted">Item Kritis</span></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-clean p-3">
            <div class="text-muted small">Total Transaksi Keluar (Outbound)</div>
            <div class="fs-2 fw-bold text-success"><?= count($riwayatKeluar) ?> <span class="fs-6 fw-normal text-muted">Transaksi</span></div>
        </div>
    </div>
</div>

<div class="card card-clean p-3 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0"><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Material Mendekati / Di Bawah Safety Stock</h5>
        <a href="views/bahan_baku/index.php" class="small text-decoration-none">Lihat semua &rarr;</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Kode</th>
                    <th>Nama Bahan</th>
                    <th>Kategori</th>
                    <th>Sisa Stok</th>
                    <th>Safety Stock</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($lowStock)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-3">Seluruh stok bahan baku dalam kondisi aman.</td></tr>
                <?php else: ?>
                    <?php foreach ($lowStock as $item): ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($item['kodeMaterial']) ?></td>
                            <td><?= htmlspecialchars($item['namaBahan']) ?> <small class="text-muted">(<?= htmlspecialchars($item['spesifikasi']) ?>)</small></td>
                            <td><?= htmlspecialchars($item['namaKategori']) ?></td>
                            <td class="fw-bold text-danger"><?= (int)$item['stok'] ?> <?= htmlspecialchars($item['satuan']) ?></td>
                            <td><?= (int)$item['safetyStock'] ?> <?= htmlspecialchars($item['satuan']) ?></td>
                            <td><span class="badge rounded-pill text-bg-danger">Perlu Restock</span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/views/layout/footer.php'; ?>
