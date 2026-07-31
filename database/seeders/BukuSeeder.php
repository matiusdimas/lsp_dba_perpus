<?php
class BukuSeeder implements SeederInterface {
    public function run(PDO $db) {
        $db->exec("INSERT IGNORE INTO buku (id_buku, id_kategori, judul, penulis, penerbit, stok) VALUES 
            ('BK-001', 1, 'Pemrograman Web dengan Laravel', 'Andi Prasetyo', 'Informatika', 5),
            ('BK-002', 2, 'Analisis dan Perancangan Sistem', 'Siti Rahma', 'Bina Media', 3),
            ('BK-003', 1, 'Basis Data Relasional & SQL', 'Budi Santoso', 'Informatika', 2),
            ('BK-004', 3, 'Manajemen Organisasi Modern', 'Dedi Wijaya', 'Ghalia Indonesia', 4);");
    }
}
