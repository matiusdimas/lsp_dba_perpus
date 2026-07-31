<?php
class CreateBukuTable implements MigrationInterface {
    public function up(PDO $db) {
        $db->exec("CREATE TABLE IF NOT EXISTS buku (
            id_buku VARCHAR(15) PRIMARY KEY,
            id_kategori INT NOT NULL,
            judul VARCHAR(150) NOT NULL,
            penulis VARCHAR(100) NOT NULL,
            penerbit VARCHAR(100) NOT NULL,
            stok INT NOT NULL DEFAULT 0,
            CONSTRAINT fk_buku_kategori FOREIGN KEY (id_kategori) 
                REFERENCES kategori(id_kategori) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT chk_stok CHECK (stok >= 0)
        ) ENGINE=InnoDB;");
    }
    public function down(PDO $db) {
        $db->exec("DROP TABLE IF EXISTS buku;");
    }
}
