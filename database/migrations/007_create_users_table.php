<?php
class CreateUsersTable implements MigrationInterface {
    public function up(PDO $db) {
        $db->exec("CREATE TABLE IF NOT EXISTS users (
            id_user INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            nama_lengkap VARCHAR(100) NOT NULL,
            role ENUM('Administrator', 'Petugas', 'Anggota') DEFAULT 'Anggota',
            id_anggota VARCHAR(15) NULL UNIQUE,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_users_anggota FOREIGN KEY (id_anggota) 
                REFERENCES anggota(id_anggota) ON DELETE SET NULL ON UPDATE CASCADE
        ) ENGINE=InnoDB;");
    }

    public function down(PDO $db) {
        $db->exec("DROP TABLE IF EXISTS users;");
    }
}
