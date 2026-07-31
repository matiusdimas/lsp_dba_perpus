<?php
class CreateDokumenTable implements MigrationInterface {
    public function up(PDO $db) {
        $db->exec("CREATE TABLE IF NOT EXISTS dokumen (
            id_dokumen INT AUTO_INCREMENT PRIMARY KEY,
            judul_dokumen VARCHAR(150) NOT NULL,
            nama_file VARCHAR(100) NOT NULL,
            jenis_file VARCHAR(10) NOT NULL,
            lokasi_file VARCHAR(255) NOT NULL,
            versi VARCHAR(10) DEFAULT '1.0',
            tgl_unggah DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB;");
    }
    public function down(PDO $db) {
        $db->exec("DROP TABLE IF EXISTS dokumen;");
    }
}
