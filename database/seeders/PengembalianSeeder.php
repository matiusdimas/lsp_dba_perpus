<?php
class PengembalianSeeder implements SeederInterface {
    public function run(PDO $db) {
        $db->exec("INSERT IGNORE INTO pengembalian (id_pengembalian, id_peminjaman, id_buku, tgl_kembali, denda) VALUES 
            ('KB-2026-001', 'PJ-2026-002', 'BK-002', '2026-07-28', 2000.00);");
    }
}
