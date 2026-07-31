<?php
class AnggotaSeeder implements SeederInterface {
    public function run(PDO $db) {
        $db->exec("INSERT IGNORE INTO anggota (id_anggota, nama, email, no_hp, alamat) VALUES 
            ('AG-001', 'Dimas Prasetia', 'dimas@gmail.com', '081234567890', 'Jakarta'),
            ('AG-002', 'Anugrah Akbar', 'akbar@gmail.com', '082198765432', 'Bekasi'),
            ('AG-003', 'Yana Fitri', 'yana@gmail.com', '083811223344', 'Depok');");
    }
}
