<?php
class CreatePengembalianTable implements MigrationInterface {
    public function up(PDO $db) {
        $db->exec("CREATE TABLE IF NOT EXISTS pengembalian (
            id_pengembalian VARCHAR(20) PRIMARY KEY,
            id_peminjaman VARCHAR(20) NOT NULL,
            id_buku VARCHAR(15) NOT NULL,
            tgl_kembali DATE NOT NULL,
            denda DECIMAL(10,2) DEFAULT 0.00,
            CONSTRAINT fk_pengembalian_peminjaman FOREIGN KEY (id_peminjaman) 
                REFERENCES peminjaman(id_peminjaman) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT fk_pengembalian_buku FOREIGN KEY (id_buku) 
                REFERENCES buku(id_buku) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB;");
    }
    public function down(PDO $db) {
        $db->exec("DROP TABLE IF EXISTS pengembalian;");
    }
}
