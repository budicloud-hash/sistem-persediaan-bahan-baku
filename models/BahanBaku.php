<?php
class BahanBaku {
    private $conn;
    private $table = "BahanBaku";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll($keyword = "", $idKategori = "") {
        $sql = "SELECT b.*, k.namaKategori
                FROM {$this->table} b
                JOIN KategoriBahan k ON b.idKategori = k.idKategori
                WHERE 1=1";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (b.kodeMaterial LIKE :kw OR b.namaBahan LIKE :kw OR b.spesifikasi LIKE :kw)";
            $params[':kw'] = "%" . $keyword . "%";
        }
        if (!empty($idKategori)) {
            $sql .= " AND b.idKategori = :idKat";
            $params[':idKat'] = (int)$idKategori;
        }

        $sql .= " ORDER BY b.kodeMaterial ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getKategoriAll() {
        $stmt = $this->conn->prepare("SELECT * FROM KategoriBahan ORDER BY namaKategori ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getLowStockItems() {
        $sql = "SELECT b.*, k.namaKategori
                FROM {$this->table} b
                JOIN KategoriBahan k ON b.idKategori = k.idKategori
                WHERE b.stok <= b.safetyStock
                ORDER BY (b.stok - b.safetyStock) ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function tambahBahan($idKategori, $kodeMaterial, $namaBahan, $spesifikasi, $satuan, $stok, $safetyStock) {
        // Cek duplikasi kodeMaterial
        $cek = $this->conn->prepare("SELECT idBahan FROM {$this->table} WHERE kodeMaterial = :kode LIMIT 1");
        $cek->execute([':kode' => $kodeMaterial]);
        if ($cek->fetch()) {
            return ["status" => false, "pesan" => "Kode material '{$kodeMaterial}' sudah terdaftar."];
        }

        $sql = "INSERT INTO {$this->table} (idKategori, kodeMaterial, namaBahan, spesifikasi, satuan, stok, safetyStock)
                VALUES (:idKat, :kode, :nama, :spek, :satuan, :stok, :safety)";
        $stmt = $this->conn->prepare($sql);
        $ok = $stmt->execute([
            ':idKat'  => (int)$idKategori,
            ':kode'   => trim($kodeMaterial),
            ':nama'   => trim($namaBahan),
            ':spek'   => trim($spesifikasi),
            ':satuan' => trim($satuan),
            ':stok'   => max(0, (int)$stok),
            ':safety' => max(0, (int)$safetyStock),
        ]);

        return $ok
            ? ["status" => true, "pesan" => "Data bahan baku '{$namaBahan}' berhasil ditambahkan."]
            : ["status" => false, "pesan" => "Gagal menyimpan data bahan baku."];
    }

    public function editBahan($idBahan, $idKategori, $namaBahan, $spesifikasi, $satuan, $safetyStock) {
        $sql = "UPDATE {$this->table}
                SET idKategori = :idKat,
                    namaBahan = :nama,
                    spesifikasi = :spek,
                    satuan = :satuan,
                    safetyStock = :safety
                WHERE idBahan = :idBahan";
        $stmt = $this->conn->prepare($sql);
        $ok = $stmt->execute([
            ':idKat'   => (int)$idKategori,
            ':nama'    => trim($namaBahan),
            ':spek'    => trim($spesifikasi),
            ':satuan'  => trim($satuan),
            ':safety'  => max(0, (int)$safetyStock),
            ':idBahan' => (int)$idBahan,
        ]);

        return $ok
            ? ["status" => true, "pesan" => "Data master bahan baku berhasil diperbarui."]
            : ["status" => false, "pesan" => "Gagal memperbarui data bahan baku."];
    }

    
    public function cekStok($idBahan) {
        $stmt = $this->conn->prepare("SELECT stok, namaBahan, satuan FROM {$this->table} WHERE idBahan = :id LIMIT 1");
        $stmt->execute([':id' => (int)$idBahan]);
        return $stmt->fetch();
    }

    
    public function updateStok($idBahan, $selisih) {
        $sql = "UPDATE {$this->table}
                SET stok = stok + :selisih
                WHERE idBahan = :idBahan AND (stok + :selisih) >= 0";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':selisih' => (int)$selisih,
            ':idBahan' => (int)$idBahan,
        ]);
        return $stmt->rowCount() > 0;
    }
}
