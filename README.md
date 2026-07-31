# Sistem Informasi Perpustakaan Kampus (LSP DBA)

Sistem Informasi Perpustakaan Kampus berbasis PHP Native MVC yang mengimplementasikan prinsip Object-Oriented Programming (OOP), SOLID Principles, Role-Based Access Control (RBAC), serta dilindungi oleh Unit Testing lengkap (PHPUnit).

## 🚀 Fitur Utama
- **Manajemen Katalog Buku:** Pencarian, penambahan, pengubahan, dan penghapusan buku.
- **Manajemen Anggota:** Pendaftaran anggota, pengelompokan akun, serta fitur **Import Data via CSV** dengan auto-generate ID.
- **Peminjaman & Pengembalian Buku:** Pencatatan transaksi peminjaman, perhitungan denda otomatis per hari terlambat (Rp 2.000/hari), dan pembaruan stok otomatis.
- **Manajemen Akun Pengguna:** Pengaturan akun multi-role (Administrator, Petugas, Anggota) dengan perlindungan hak akses ketat (RBAC).
- **Arsip Dokumen:** Pengelolaan dokumen internal dan publik.
- **Unit Testing:** Teruji dengan 57 test cases (100% Passed) menggunakan PHPUnit.

## 🛠️ Persyaratan Sistem
- PHP >= 8.1 (dengan ekstensi `pdo_mysql`, `mbstring`)
- MySQL / MariaDB
- Composer

## 🔧 Cara Memulai
1. **Clone repository:**
   ```bash
   git clone https://github.com/matiusdimas/lsp_dba_perpus.git
   cd lsp_dba_perpus
   ```
2. **Install Dependensi:**
   ```bash
   composer install
   ```
3. **Konfigurasi Database:**
   Impor file `01_db_perpustakaan_full.sql` ke MySQL Anda atau jalankan migrasi:
   ```bash
   php console.php migrate
   ```
4. **Jalankan Development Server:**
   ```bash
   php -S localhost:8000 -t public
   ```
5. **Jalankan Testing:**
   ```bash
   vendor/bin/phpunit --testdox
   ```

## 📜 Lisensi
MIT License.
