<?php
class KategoriSeeder implements SeederInterface {
    public function run(PDO $db) {
        $db->exec("INSERT IGNORE INTO kategori (id_kategori, nama_kategori) VALUES 
            (1, 'Informatika'),
            (2, 'Sistem Informasi'),
            (3, 'Manajemen');");
    }
}
