<?php
require_once __DIR__ . '/BahanBaku.php';

class MaterialKeluar {
    private $conn;
    private $table = "MaterialKeluar";
    private $bahanModel;

    public function __construct($db) {
        $this->conn = $db;
        $this->bahanModel = new BahanBaku($db);
    }

    public function getLapKeluar() {
        $sql = "SELECT mk.*, b.kodeMaterial, b.namaBahan, b.spesifikasi, b.satuan, u.username, u.role
                FROM {$this->table} mk
                JOIN BahanBaku b ON mk.idBahan = b.idBahan
                JOIN User u ON mk.idUser = u.idUser
                ORDER BY mk.tanggal DESC, mk.idKeluar DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function simpanKeluar($idBahan, $idUser, $jumlahKeluar, $noWorkOrder, $tanggal) {
        $idBahan = (int)$idBahan;
        $idUser = (int)$idUser;
        $jumlahKeluar = (int)$jumlahKeluar;
        $noWorkOrder = trim($noWorkOrder);

        if ($jumlahKeluar <= 0 || empty($noWorkOrder) || $idBahan <= 0) {
            return ["status" => false, "pesan" => "Nomor Work Order, bahan baku, dan jumlah wajib diisi dengan benar."];
        }

        
        $infoBahan = $this->bahanModel->cekStok($idBahan);
        if (!$infoBahan) {
            return ["status" => false, "pesan" => "Data bahan baku tidak ditemukan."];
        }

        $stokTersedia = (int)$infoBahan['stok'];
        if ($stokTersedia < $jumlahKeluar) {
            return [
                "status" => false,
                "pesan"  => "Stok tidak mencukupi! Stok '{$infoBahan['namaBahan']}' tersedia: {$stokTersedia} {$infoBahan['satuan']}, diminta: {$jumlahKeluar} {$infoBahan['satuan']}."
            ];
        }

        
        try {
            $this->conn->beginTransaction();

            $sql = "INSERT INTO {$this->table} (idBahan, idUser, jumlahKeluar, noWorkOrder, tanggal)
                    VALUES (:idBahan, :idUser, :jumlah, :wo, :tanggal)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':idBahan' => $idBahan,
                ':idUser'  => $idUser,
                ':jumlah'  => $jumlahKeluar,
                ':wo'      => $noWorkOrder,
                ':tanggal' => $tanggal,
            ]);

            $updated = $this->bahanModel->updateStok($idBahan, -$jumlahKeluar);
            if (!$updated) {
                throw new Exception("Gagal mengurangi stok pada tabel BahanBaku.");
            }

            $this->conn->commit();
            $sisaBaru = $stokTersedia - $jumlahKeluar;
            return [
                "status" => true,
                "pesan"  => "Pengeluaran {$jumlahKeluar} {$infoBahan['satuan']} '{$infoBahan['namaBahan']}' (WO: {$noWorkOrder}) berhasil. Sisa stok: {$sisaBaru} {$infoBahan['satuan']}."
            ];
        } catch (Exception $e) {
            $this->conn->rollBack();
            return ["status" => false, "pesan" => "Terjadi kesalahan transaksi: " . $e->getMessage()];
        }
    }
}
