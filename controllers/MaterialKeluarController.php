<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/BahanBaku.php';
require_once __DIR__ . '/../models/MaterialKeluar.php';

class MaterialKeluarController {
    private $keluarModel;
    private $bahanModel;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->keluarModel = new MaterialKeluar($db);
        $this->bahanModel = new BahanBaku($db);
    }

    public function handleRequest($activeUserId = 1) {
        $flash = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'simpan_keluar') {
                $flash = $this->keluarModel->simpanKeluar(
                    $_POST['idBahan'] ?? 0,
                    $activeUserId,
                    $_POST['jumlahKeluar'] ?? 0,
                    $_POST['noWorkOrder'] ?? '',
                    $_POST['tanggal'] ?? date('Y-m-d')
                );
            }
        }

        return [
            'flash'       => $flash,
            'bahanList'   => $this->bahanModel->getAll(),
            'riwayatList' => $this->keluarModel->getLapKeluar(),
        ];
    }
}
