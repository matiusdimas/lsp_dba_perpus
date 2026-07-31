<?php
class AddIsActiveToUsersTable implements MigrationInterface {
    public function up(PDO $db) {
        $db->exec("ALTER TABLE users ADD COLUMN is_active TINYINT(1) DEFAULT 1 AFTER id_anggota;");
    }

    public function down(PDO $db) {
        $db->exec("ALTER TABLE users DROP COLUMN is_active;");
    }
}
