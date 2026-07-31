<?php
class CreateKategoriTable implements MigrationInterface {
    public function up(PDO $db) {
        $db->exec("CREATE TABLE IF NOT EXISTS kategori (
            id_kategori INT AUTO_INCREMENT PRIMARY KEY,
            nama_kategori VARCHAR(50) NOT NULL
        ) ENGINE=InnoDB;");
    }
    public function down(PDO $db) {
        $db->exec("DROP TABLE IF EXISTS kategori;");
    }
}
