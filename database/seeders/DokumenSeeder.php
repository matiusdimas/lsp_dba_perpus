<?php
class DokumenSeeder implements SeederInterface {
    public function run(PDO $db) {
        $db->exec("INSERT IGNORE INTO dokumen (judul_dokumen, nama_file, jenis_file, lokasi_file, versi) VALUES 
            ('Panduan Tata Tertib Perpustakaan 2026', 'tatib_2026.pdf', 'pdf', '/uploads/docs/tatib_2026.pdf', '1.0'),
            ('Formulir Pendaftaran Anggota Baru', 'form_anggota.docx', 'docx', '/uploads/docs/form_anggota.docx', '2.1'),
            ('SOP Denda dan Pengembalian', 'sop_denda.pdf', 'pdf', '/uploads/docs/sop_denda.pdf', '1.2');");
    }
}
