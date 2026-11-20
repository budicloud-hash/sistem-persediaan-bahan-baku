# Sistem Informasi Pengelolaan Persediaan Bahan Baku (Raw Material) Berbasis Web

Proyek Mata Kuliah **TIF708 - Proyek Sistem Informasi**  
Universitas Pelita Bangsa — Kelas 1239A (TA 2026/2027)

## Anggota Kelompok BBS (Behavior Base Source)
1. **Budi Adi Saputra** (312310747) — Project Manager (PO/SM) & Business Analyst
2. **Daffa Tris Sriprasta** (312310118) — UI/UX Designer & Frontend Developer
3. **Sultan Albani Toha** (312310705) — Backend Developer & Database Specialist
4. **Iis Supriyati** (312310797) — QA / System Tester & Technical Writer

## Struktur Folder
- `config/` : Konfigurasi koneksi PDO MySQL/MariaDB.
- `models/` : Kelas domain OOP (`BahanBaku` dan `MaterialKeluar`).
- `controllers/` : Pengendali alur request & integrasi antar-modul.
- `views/` : Antarmuka pengguna berbasis Bootstrap 5 (`Dashboard`, `Master Bahan Baku`, `Transaksi Keluar`).
- `database/` : Skrip DDL 5 tabel 3NF (`gudang_bahan_baku.sql`) dan data sampel.
- `assets/` : File CSS kustom dan JavaScript interaksi form.

## Cara Menjalankan di Localhost (XAMPP / Laragon)
1. Salin folder `sistem-persediaan-bahan-baku` ke direktori `htdocs/` (XAMPP) atau `www/` (Laragon).
2. Buka **phpMyAdmin**, buat database `gudang_bahan_baku`, lalu impor file `database/gudang_bahan_baku.sql`.
3. Sesuaikan kredensial database pada `config/database.php` jika diperlukan (default: `root`, password kosong).
4. Akses melalui peramban: `http://localhost/sistem-persediaan-bahan-baku/`.
