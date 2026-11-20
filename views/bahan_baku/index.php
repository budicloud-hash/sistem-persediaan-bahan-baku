<?php
require_once __DIR__ . '/../../controllers/BahanBakuController.php';

$controller = new BahanBakuController();
$data = $controller->handleRequest();

$flash = $data['flash'];
$bahanList = $data['bahanList'];
$kategoriList = $data['kategori'];

$activePage = 'bahan_baku';
$baseUrl = '../..';
include __DIR__ . '/../layout/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Modul 1: Master Data Bahan Baku</h3>
        <p class="text-muted mb-0">Kelola daftar material, spesifikasi, satuan, dan batas safety stock.</p>
    </div>
    <button class="btn btn-primary-navy" data-bs-toggle="modal" data-bs-target="#modalTambahBahan">
        <i class="bi bi-plus-lg me-1"></i>Tambah Material
    </button>
</div>

<?php if ($flash): ?>
    <div class="alert alert-<?= $flash['status'] ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($flash['pesan']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Filter & Pencarian -->
<div class="card card-clean p-3 mb-3">
    <form method="GET" class="row g-2">
        <div class="col-md-6">
            <input type="text" name="q" class="form-control" placeholder="Cari kode atau nama material..." value="<?= htmlspecialchars($data['keyword']) ?>">
        </div>
        <div class="col-md-4">
            <select name="kategori" class="form-select">
                <option value="">Semua Kategori</option>
                <?php foreach ($kategoriList as $kat): ?>
                    <option value="<?= $kat['idKategori'] ?>" <?= $data['katFilter'] == $kat['idKategori'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($kat['namaKategori']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2 d-grid">
            <button type="submit" class="btn btn-outline-secondary">Filter</button>
        </div>
    </form>
</div>

<!-- Tabel Master Bahan Baku -->
<div class="card card-clean p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Kode</th>
                    <th>Nama Bahan</th>
                    <th>Kategori</th>
                    <th>Spesifikasi</th>
                    <th>Stok Saat Ini</th>
                    <th>Safety Stock</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($bahanList)): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data bahan baku.</td></tr>
                <?php else: ?>
                    <?php foreach ($bahanList as $row): ?>
                        <?php $isKritis = ((int)$row['stok'] <= (int)$row['safetyStock']); ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($row['kodeMaterial']) ?></td>
                            <td><?= htmlspecialchars($row['namaBahan']) ?></td>
                            <td><?= htmlspecialchars($row['namaKategori']) ?></td>
                            <td><?= htmlspecialchars($row['spesifikasi']) ?></td>
                            <td class="fw-bold <?= $isKritis ? 'text-danger' : 'text-dark' ?>">
                                <?= (int)$row['stok'] ?> <?= htmlspecialchars($row['satuan']) ?>
                            </td>
                            <td><?= (int)$row['safetyStock'] ?> <?= htmlspecialchars($row['satuan']) ?></td>
                            <td>
                                <?php if ($isKritis): ?>
                                    <span class="badge rounded-pill text-bg-danger">Kritis / Restock</span>
                                <?php else: ?>
                                    <span class="badge rounded-pill text-bg-success">Aman</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEdit<?= $row['idBahan'] ?>">
                                    Edit
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Edit Bahan -->
                        <div class="modal fade" id="modalEdit<?= $row['idBahan'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST" class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Bahan Baku (<?= htmlspecialchars($row['kodeMaterial']) ?>)</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="action" value="edit">
                                        <input type="hidden" name="idBahan" value="<?= $row['idBahan'] ?>">
                                        <div class="mb-2">
                                            <label class="form-label">Kategori</label>
                                            <select name="idKategori" class="form-select" required>
                                                <?php foreach ($kategoriList as $kat): ?>
                                                    <option value="<?= $kat['idKategori'] ?>" <?= $kat['idKategori'] == $row['idKategori'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($kat['namaKategori']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Nama Bahan</label>
                                            <input type="text" name="namaBahan" class="form-control" value="<?= htmlspecialchars($row['namaBahan']) ?>" required>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Spesifikasi</label>
                                            <input type="text" name="spesifikasi" class="form-control" value="<?= htmlspecialchars($row['spesifikasi']) ?>">
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <label class="form-label">Satuan</label>
                                                <input type="text" name="satuan" class="form-control" value="<?= htmlspecialchars($row['satuan']) ?>" required>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Safety Stock</label>
                                                <input type="number" name="safetyStock" class="form-control" min="0" value="<?= (int)$row['safetyStock'] ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary-navy">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Bahan Baru -->
<div class="modal fade" id="modalTambahBahan" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Master Bahan Baku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="action" value="tambah">
                <div class="row g-2 mb-2">
                    <div class="col-5">
                        <label class="form-label">Kode Material *</label>
                        <input type="text" name="kodeMaterial" class="form-control" placeholder="RM-05" required>
                    </div>
                    <div class="col-7">
                        <label class="form-label">Kategori *</label>
                        <select name="idKategori" class="form-select" required>
                            <?php foreach ($kategoriList as $kat): ?>
                                <option value="<?= $kat['idKategori'] ?>"><?= htmlspecialchars($kat['namaKategori']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label">Nama Bahan *</label>
                    <input type="text" name="namaBahan" class="form-control" placeholder="Contoh: Karet Seal O-Ring" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Spesifikasi</label>
                    <input type="text" name="spesifikasi" class="form-control" placeholder="Contoh: Diameter 25mm">
                </div>
                <div class="row g-2">
                    <div class="col-4">
                        <label class="form-label">Satuan *</label>
                        <input type="text" name="satuan" class="form-control" placeholder="Pcs / Kg" required>
                    </div>
                    <div class="col-4">
                        <label class="form-label">Stok Awal *</label>
                        <input type="number" name="stok" class="form-control" min="0" value="0" required>
                    </div>
                    <div class="col-4">
                        <label class="form-label">Safety Stock *</label>
                        <input type="number" name="safetyStock" class="form-control" min="0" value="10" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary-navy">Simpan Material</button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
