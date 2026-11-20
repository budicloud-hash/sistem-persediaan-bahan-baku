<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/BahanBaku.php';

class BahanBakuController {
    private $model;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->model = new BahanBaku($db);
    }

    public function handleRequest() {
        $flash = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'tambah') {
                $flash = $this->model->tambahBahan(
                    $_POST['idKategori'] ?? 0,
                    $_POST['kodeMaterial'] ?? '',
                    $_POST['namaBahan'] ?? '',
                    $_POST['spesifikasi'] ?? '',
                    $_POST['satuan'] ?? '',
                    $_POST['stok'] ?? 0,
                    $_POST['safetyStock'] ?? 0
                );
            } elseif ($action === 'edit') {
                $flash = $this->model->editBahan(
                    $_POST['idBahan'] ?? 0,
                    $_POST['idKategori'] ?? 0,
                    $_POST['namaBahan'] ?? '',
                    $_POST['spesifikasi'] ?? '',
                    $_POST['satuan'] ?? '',
                    $_POST['safetyStock'] ?? 0
                );
            }
        }

        $keyword = $_GET['q'] ?? '';
        $kategoriFilter = $_GET['kategori'] ?? '';

        return [
            'flash'      => $flash,
            'bahanList'  => $this->model->getAll($keyword, $kategoriFilter),
            'kategori'   => $this->model->getKategoriAll(),
            'lowStock'   => $this->model->getLowStockItems(),
            'keyword'    => $keyword,
            'katFilter'  => $kategoriFilter,
        ];
    }
}
