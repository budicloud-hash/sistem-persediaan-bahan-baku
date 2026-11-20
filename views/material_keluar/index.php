<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../controllers/MaterialKeluarController.php';

$activePage = 'material_keluar';
$baseUrl = '../..';
include __DIR__ . '/../layout/header.php';

$controller = new MaterialKeluarController();
$data = $controller->handleRequest($activeUser['idUser']);

$flash = $data['flash'];
$bahanList = $data['bahanList'];
$riwayatList = $data['riwayatList'];
?>

<div class="mb-4">
    <h3 class="fw-bold mb-1">Modul 2: Transaksi Pengeluaran Bahan Baku (Outbound)</h3>
    <p class="text-muted mb-0">Terintegrasi langsung dengan stok pada Master Data Bahan Baku.</p>
</div>

<?php if ($flash): ?>
    <div class="alert alert-<?= $flash['status'] ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($flash['pesan']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-4">
    <!-- Form Permintaan & Pengeluaran -->
    <div class="col-lg-5">
        <div class="card card-clean p-4">
            <h5 class="fw-bold mb-3">Form Pengeluaran Material</h5>
            <form method="POST">
                <input type="hidden" name="action" value="simpan_keluar">
                <div class="mb-3">
                    <label class="form-label">Nomor Work Order (WO) *</label>
                    <input type="text" name="noWorkOrder" class="form-control" placeholder="Contoh: WO-2026-002" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Pilih Bahan Baku *</label>
                    <select name="idBahan" id="selectBahanKeluar" class="form-select" required>
                        <option value="">-- Pilih Material --</option>
                        <?php foreach ($bahanList as $b): ?>
                            <option value="<?= $b['idBahan'] ?>"
                                    data-stok="<?= (int)$b['stok'] ?>"
                                    data-satuan="<?= htmlspecialchars($b['satuan']) ?>">
                                <?= htmlspecialchars($b['kodeMaterial']) ?> — <?= htmlspecialchars($b['namaBahan']) ?> (Stok: <?= (int)$b['stok'] ?> <?= htmlspecialchars($b['satuan']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div id="infoStokTersedia" class="form-text text-primary fw-semibold">Pilih bahan baku untuk melihat sisa stok.</div>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label">Jumlah Keluar *</label>
                        <input type="number" name="jumlahKeluar" class="form-control" min="1" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Tanggal *</label>
                        <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-3 mt-4">
                    <button type="reset" class="btn btn-outline-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary-navy">Proses & Kurangi Stok</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Riwayat Transaksi Keluar -->
    <div class="col-lg-7">
        <div class="card card-clean p-4">
            <h5 class="fw-bold mb-3">Riwayat Transaksi Material Keluar</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>No. WO</th>
                            <th>Material</th>
                            <th>Jumlah</th>
                            <th>Diproses Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($riwayatList)): ?>
                            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada transaksi pengeluaran.</td></tr>
                        <?php else: ?>
                            <?php foreach ($riwayatList as $r): ?>
                                <tr>
                                    <td><?= htmlspecialchars($r['tanggal']) ?></td>
                                    <td><span class="badge text-bg-light border"><?= htmlspecialchars($r['noWorkOrder']) ?></span></td>
                                    <td>
                                        <div class="fw-semibold"><?= htmlspecialchars($r['kodeMaterial']) ?> — <?= htmlspecialchars($r['namaBahan']) ?></div>
                                        <small class="text-muted"><?= htmlspecialchars($r['spesifikasi']) ?></small>
                                    </td>
                                    <td class="fw-bold text-danger">-<?= (int)$r['jumlahKeluar'] ?> <?= htmlspecialchars($r['satuan']) ?></td>
                                    <td>
                                        <div><?= htmlspecialchars($r['username']) ?></div>
                                        <small class="text-muted"><?= htmlspecialchars($r['role']) ?></small>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
