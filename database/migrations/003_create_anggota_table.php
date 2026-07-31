<?php
class CreateAnggotaTable implements MigrationInterface {
    public function up(PDO $db) {
        $db->exec("CREATE TABLE IF NOT EXISTS anggota (
            id_anggota VARCHAR(15) PRIMARY KEY,
            nama VARCHAR(100) NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            no_hp VARCHAR(20),
            alamat TEXT
        ) ENGINE=InnoDB;");
    }
    public function down(PDO $db) {
        $db->exec("DROP TABLE IF EXISTS anggota;");
    }
}
