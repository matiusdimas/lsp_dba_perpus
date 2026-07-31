<?php
class CreatePeminjamanTable implements MigrationInterface {
    public function up(PDO $db) {
        $db->exec("CREATE TABLE IF NOT EXISTS peminjaman (
            id_peminjaman VARCHAR(20) PRIMARY KEY,
            id_anggota VARCHAR(15) NOT NULL,
            id_buku VARCHAR(15) NOT NULL,
            tgl_pinjam DATE NOT NULL,
            tgl_jatuh_tempo DATE NOT NULL,
            status ENUM('Dipinjam', 'Selesai') DEFAULT 'Dipinjam',
            CONSTRAINT fk_peminjaman_anggota FOREIGN KEY (id_anggota) 
                REFERENCES anggota(id_anggota) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT fk_peminjaman_buku FOREIGN KEY (id_buku) 
                REFERENCES buku(id_buku) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB;");
    }
    public function down(PDO $db) {
        $db->exec("DROP TABLE IF EXISTS peminjaman;");
    }
}
