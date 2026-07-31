<?php
class PeminjamanSeeder implements SeederInterface {
    public function run(PDO $db) {
        $db->exec("INSERT IGNORE INTO peminjaman (id_peminjaman, id_anggota, id_buku, tgl_pinjam, tgl_jatuh_tempo, status) VALUES 
            ('PJ-2026-001', 'AG-001', 'BK-001', '2026-08-01', '2026-08-08', 'Dipinjam'),
            ('PJ-2026-002', 'AG-002', 'BK-002', '2026-07-20', '2026-07-27', 'Selesai');");
    }
}
