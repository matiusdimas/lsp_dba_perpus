-- ============================================================================
-- LANGKAH 3: MEMBUAT DATABASE DAN TABEL (DDL)
-- ============================================================================

CREATE DATABASE IF NOT EXISTS db_perpustakaan_kampus;
USE db_perpustakaan_kampus;

-- 1. Tabel Kategori
CREATE TABLE IF NOT EXISTS kategori (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

-- 2. Tabel Buku
CREATE TABLE IF NOT EXISTS buku (
    id_buku VARCHAR(15) PRIMARY KEY,
    id_kategori INT NOT NULL,
    judul VARCHAR(150) NOT NULL,
    penulis VARCHAR(100) NOT NULL,
    penerbit VARCHAR(100) NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    CONSTRAINT fk_buku_kategori FOREIGN KEY (id_kategori) 
        REFERENCES kategori(id_kategori) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT chk_stok CHECK (stok >= 0)
) ENGINE=InnoDB;

-- 3. Tabel Anggota
CREATE TABLE IF NOT EXISTS anggota (
    id_anggota VARCHAR(15) PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    no_hp VARCHAR(20),
    alamat TEXT
) ENGINE=InnoDB;

-- 4. Tabel Peminjaman (Header Transaksi)
CREATE TABLE IF NOT EXISTS peminjaman (
    id_peminjaman VARCHAR(20) PRIMARY KEY,
    id_anggota VARCHAR(15) NOT NULL,
    tgl_pinjam DATE NOT NULL,
    tgl_jatuh_tempo DATE NOT NULL,
    status ENUM('Dipinjam', 'Selesai') DEFAULT 'Dipinjam',
    CONSTRAINT fk_peminjaman_anggota FOREIGN KEY (id_anggota) 
        REFERENCES anggota(id_anggota) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 5. Tabel Pengembalian (Detail / Denda Transaksi)
CREATE TABLE IF NOT EXISTS pengembalian (
    id_pengembalian VARCHAR(20) PRIMARY KEY,
    id_peminjaman VARCHAR(20) NOT NULL,
    id_buku VARCHAR(15) NOT NULL,
    tgl_kembali DATE NOT NULL,
    denda DECIMAL(10,2) DEFAULT 0.00,
    CONSTRAINT fk_pengembalian_peminjaman FOREIGN KEY (id_peminjaman) 
        REFERENCES peminjaman(id_peminjaman) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_pengembalian_buku FOREIGN KEY (id_buku) 
        REFERENCES buku(id_buku) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 6. Tabel Dokumen (Metadata Pengarsipan Digital)
CREATE TABLE IF NOT EXISTS dokumen (
    id_dokumen INT AUTO_INCREMENT PRIMARY KEY,
    judul_dokumen VARCHAR(150) NOT NULL,
    nama_file VARCHAR(100) NOT NULL,
    jenis_file VARCHAR(10) NOT NULL,
    lokasi_file VARCHAR(255) NOT NULL,
    versi VARCHAR(10) DEFAULT '1.0',
    tgl_unggah DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


-- ============================================================================
-- LANGKAH 4: MENGISI DATA CONTOH (DUMMY DATA - DML)
-- ============================================================================

-- Insert Kategori
INSERT INTO kategori (nama_kategori) VALUES 
('Informatika'),
('Sistem Informasi'),
('Manajemen');

-- Insert Buku
INSERT INTO buku (id_buku, id_kategori, judul, penulis, penerbit, stok) VALUES 
('BK-001', 1, 'Pemrograman Web dengan Laravel', 'Andi Prasetyo', 'Informatika', 5),
('BK-002', 2, 'Analisis dan Perancangan Sistem', 'Siti Rahma', 'Bina Media', 3),
('BK-003', 1, 'Basis Data Relasional & SQL', 'Budi Santoso', 'Informatika', 2),
('BK-004', 3, 'Manajemen Organisasi Modern', 'Dedi Wijaya', 'Ghalia Indonesia', 4);

-- Insert Anggota
INSERT INTO anggota (id_anggota, nama, email, no_hp, alamat) VALUES 
('AG-001', 'Dimas Prasetia', 'dimas@gmail.com', '081234567890', 'Jakarta'),
('AG-002', 'Anugrah Akbar', 'akbar@gmail.com', '082198765432', 'Bekasi'),
('AG-003', 'Yana Fitri', 'yana@gmail.com', '083811223344', 'Depok');

-- Insert Transaksi Peminjaman
INSERT INTO peminjaman (id_peminjaman, id_anggota, tgl_pinjam, tgl_jatuh_tempo, status) VALUES 
('PJ-2026-001', 'AG-001', '2026-08-01', '2026-08-08', 'Dipinjam'),
('PJ-2026-002', 'AG-002', '2026-07-20', '2026-07-27', 'Selesai');

-- Insert Transaksi Pengembalian
INSERT INTO pengembalian (id_pengembalian, id_peminjaman, id_buku, tgl_kembali, denda) VALUES 
('KB-2026-001', 'PJ-2026-002', 'BK-002', '2026-07-28', 2000.00);

-- Insert Metadata Dokumen (Langkah 8)
INSERT INTO dokumen (judul_dokumen, nama_file, jenis_file, lokasi_file, versi) VALUES 
('Panduan Tata Tertib Perpustakaan 2026', 'tatib_2026.pdf', 'pdf', '/uploads/docs/tatib_2026.pdf', '1.0'),
('Formulir Pendaftaran Anggota Baru', 'form_anggota.docx', 'docx', '/uploads/docs/form_anggota.docx', '2.1'),
('SOP Denda dan Pengembalian', 'sop_denda.pdf', 'pdf', '/uploads/docs/sop_denda.pdf', '1.2');


-- ============================================================================
-- LANGKAH 5: QUERY SQL UNTUK OPERASI BISNIS & LAPORAN
-- ============================================================================

-- A. Menampilkan Buku yang Tersedia dan Sedang Dipinjam
SELECT b.id_buku, b.judul, k.nama_kategori, b.stok 
FROM buku b 
JOIN kategori k ON b.id_kategori = k.id_kategori 
WHERE b.stok > 0;

-- B. Laporan Peminjaman Aktif (Buku yang Sedang Dipinjam)
SELECT p.id_peminjaman, a.nama AS nama_peminjam, p.tgl_pinjam, p.tgl_jatuh_tempo, p.status 
FROM peminjaman p 
JOIN anggota a ON p.id_anggota = a.id_anggota 
WHERE p.status = 'Dipinjam';

-- C. Pencarian Buku Berdasarkan Judul/Penulis
SELECT * FROM buku 
WHERE judul LIKE '%Laravel%' OR penulis LIKE '%Andi%';


-- ============================================================================
-- LANGKAH 6 & 7: INTEGRASI CSV & PENGELOLAAN KUALITAS DATA
-- ============================================================================

/*
 Catatan Import CSV di MySQL Command Line/phpMyAdmin:
 LOAD DATA INFILE '/path/to/data_anggota.csv'
 INTO TABLE anggota
 FIELDS TERMINATED BY ',' ENCLOSED BY '"'
 LINES TERMINATED BY '\n'
 IGNORE 1 ROWS;
*/

-- Identifikasi Data Ganda berdasarkan Email (Pemeriksaan Kualitas Data)
SELECT email, COUNT(*) as jumlah 
FROM anggota 
GROUP BY email 
HAVING jumlah > 1;

-- Menghapus / Memperbaiki Data Ganda (Menjaga data dengan id_anggota terkecil)
DELETE a1 FROM anggota a1
INNER JOIN anggota a2 
WHERE a1.id_anggota > a2.id_anggota AND a1.email = a2.email;

-- Identifikasi Data Kosong / Format Tidak sesuai
SELECT * FROM anggota WHERE nama IS NULL OR email IS NULL OR email NOT LIKE '%@%';

-- Memperbaiki Stok Buku Tidak Valid (Misal ada stok bernilai negatif)
UPDATE buku SET stok = 0 WHERE stok < 0;


-- ============================================================================
-- LANGKAH 9: PENERAPAN HAK AKSES BASIS DATA (DCL)
-- ============================================================================

-- 1. Buat User Administrator (Hak Akses Penuh)
CREATE USER IF NOT EXISTS 'admin_perpus'@'localhost' IDENTIFIED BY 'AdminSecure123!';
GRANT ALL PRIVILEGES ON db_perpustakaan_kampus.* TO 'admin_perpus'@'localhost';

-- 2. Buat User Petugas Perpustakaan (Hak Akses Operasional / Terbatas)
CREATE USER IF NOT EXISTS 'petugas_perpus'@'localhost' IDENTIFIED BY 'PetugasPass123!';
GRANT SELECT, INSERT, UPDATE ON db_perpustakaan_kampus.peminjaman TO 'petugas_perpus'@'localhost';
GRANT SELECT, INSERT, UPDATE ON db_perpustakaan_kampus.pengembalian TO 'petugas_perpus'@'localhost';
GRANT SELECT ON db_perpustakaan_kampus.buku TO 'petugas_perpus'@'localhost';
GRANT SELECT ON db_perpustakaan_kampus.anggota TO 'petugas_perpus'@'localhost';

-- Apply Changes
FLUSH PRIVILEGES;


-- ============================================================================
-- LANGKAH 10: BACKUP BASIS DATA
-- ============================================================================
/*
 Perintah untuk melaksanaan Backup Basis Data via Terminal / Command Prompt:
 mysqldump -u root -p db_perpustakaan_kampus > backup_db_perpustakaan_kampus.sql
*/