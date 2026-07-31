<?php
class UserSeeder implements SeederInterface {
    public function run(PDO $db) {
        $adminPass   = password_hash('admin123', PASSWORD_BCRYPT);
        $petugasPass = password_hash('petugas123', PASSWORD_BCRYPT);
        $anggotaPass = password_hash('anggota123', PASSWORD_BCRYPT);

        $stmt = $db->prepare(
            "INSERT IGNORE INTO users (username, password, nama_lengkap, role, id_anggota) VALUES 
            ('admin',   ?, 'Administrator Sistem', 'Administrator', NULL),
            ('petugas', ?, 'Petugas Perpustakaan', 'Petugas', NULL),
            ('anggota', ?, 'Dimas Prasetia', 'Anggota', 'AG-001')"
        );
        $stmt->execute([$adminPass, $petugasPass, $anggotaPass]);
    }
}
