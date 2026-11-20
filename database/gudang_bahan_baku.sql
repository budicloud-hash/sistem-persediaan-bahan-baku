-- Database: gudang_bahan_baku
-- Proyek: Sistem Informasi Pengelolaan Persediaan Bahan Baku (Raw Material)
-- Kelompok BBS - Universitas Pelita Bangsa

CREATE DATABASE IF NOT EXISTS gudang_bahan_baku CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gudang_bahan_baku;

DROP TABLE IF EXISTS MaterialKeluar;
DROP TABLE IF EXISTS MaterialMasuk;
DROP TABLE IF EXISTS BahanBaku;
DROP TABLE IF EXISTS KategoriBahan;
DROP TABLE IF EXISTS User;

CREATE TABLE User (
    idUser INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(30) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE KategoriBahan (
    idKategori INT AUTO_INCREMENT PRIMARY KEY,
    namaKategori VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE BahanBaku (
    idBahan INT AUTO_INCREMENT PRIMARY KEY,
    idKategori INT NOT NULL,
    kodeMaterial VARCHAR(30) NOT NULL UNIQUE,
    namaBahan VARCHAR(100) NOT NULL,
    spesifikasi VARCHAR(150),
    satuan VARCHAR(20) NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    safetyStock INT NOT NULL DEFAULT 0,
    CONSTRAINT fk_bahan_kategori FOREIGN KEY (idKategori) REFERENCES KategoriBahan(idKategori)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE MaterialMasuk (
    idMasuk INT AUTO_INCREMENT PRIMARY KEY,
    idBahan INT NOT NULL,
    idUser INT NOT NULL,
    jumlahMasuk INT NOT NULL,
    supplier VARCHAR(100) NOT NULL,
    tanggal DATE NOT NULL,
    CONSTRAINT fk_masuk_bahan FOREIGN KEY (idBahan) REFERENCES BahanBaku(idBahan)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_masuk_user FOREIGN KEY (idUser) REFERENCES User(idUser)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE MaterialKeluar (
    idKeluar INT AUTO_INCREMENT PRIMARY KEY,
    idBahan INT NOT NULL,
    idUser INT NOT NULL,
    jumlahKeluar INT NOT NULL,
    noWorkOrder VARCHAR(50) NOT NULL,
    tanggal DATE NOT NULL,
    CONSTRAINT fk_keluar_bahan FOREIGN KEY (idBahan) REFERENCES BahanBaku(idBahan)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_keluar_user FOREIGN KEY (idUser) REFERENCES User(idUser)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Data Awal (Password default seluruh akun: password)
INSERT INTO User (idUser, username, password, role) VALUES
(1, 'budi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin Gudang'),
(2, 'daffa', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Bagian Produksi'),
(3, 'sultan', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Manajer Gudang');

INSERT INTO KategoriBahan (idKategori, namaKategori) VALUES
(1, 'Logam'),
(2, 'Pengikat'),
(3, 'Kimia & Pelumas');

INSERT INTO BahanBaku (idBahan, idKategori, kodeMaterial, namaBahan, spesifikasi, satuan, stok, safetyStock) VALUES
(1, 1, 'RM-01', 'Plat Baja', 'Tebal 2mm', 'Lembar', 80, 50),
(2, 2, 'RM-02', 'Baut M8', 'Baja Hitam', 'Pcs', 200, 100),
(3, 1, 'RM-03', 'Aluminium Sheet', 'Tebal 1.5mm', 'Lembar', 35, 40),
(4, 3, 'RM-04', 'Oli Hidrolik ISO 46', 'Kemasan Drum 20L', 'Liter', 120, 60);

INSERT INTO MaterialMasuk (idMasuk, idBahan, idUser, jumlahMasuk, supplier, tanggal) VALUES
(1, 1, 1, 100, 'PT Steel Indonesia', '2026-10-01'),
(2, 2, 1, 200, 'CV Jaya Makmur', '2026-10-02');

INSERT INTO MaterialKeluar (idKeluar, idBahan, idUser, jumlahKeluar, noWorkOrder, tanggal) VALUES
(1, 1, 2, 20, 'WO-2026-001', '2026-10-05');
