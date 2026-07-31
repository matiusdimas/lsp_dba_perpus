<?php

class SqlController extends Controller {

    public function __construct() {
        $this->requireRole('Administrator');
    }

    public function index() {
        $queryKey = $_GET['query'] ?? 'buku_tersedia';
        $sqlModel = $this->model('BukuModel');

        $queries = [
            'buku_tersedia'    => "SELECT id_buku, judul, stok FROM buku WHERE stok > 0",
            'peminjaman_aktif' => "SELECT p.id_peminjaman, a.nama, p.tgl_pinjam, p.tgl_jatuh_tempo FROM peminjaman p JOIN anggota a ON p.id_anggota = a.id_anggota WHERE p.status = 'Dipinjam'",
            'semua_anggota'    => "SELECT id_anggota, nama, email, no_hp FROM anggota ORDER BY id_anggota",
            'semua_buku'       => "SELECT b.id_buku, b.judul, k.nama_kategori, b.stok FROM buku b JOIN kategori k ON b.id_kategori = k.id_kategori",
            'pengembalian'     => "SELECT pr.id_pengembalian, pr.id_peminjaman, b.judul, pr.tgl_kembali, pr.denda FROM pengembalian pr JOIN buku b ON pr.id_buku = b.id_buku",
            'data_ganda'       => "SELECT email, COUNT(*) AS jumlah FROM anggota GROUP BY email HAVING COUNT(*) > 1",
            'stok_tidak_valid' => "SELECT id_buku, judul, stok FROM buku WHERE stok < 0",
            'struktur_relasi'  => "SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE REFERENCED_TABLE_SCHEMA = 'db_perpustakaan_kampus'",
        ];

        $sql = $queries[$queryKey] ?? $queries['buku_tersedia'];

        $db = $sqlModel->getDb();
        $stmt = $db->query($sql);
        $results = $stmt ? $stmt->fetchAll() : [];

        $columns = !empty($results) ? array_keys($results[0]) : [];

        $data['queries']   = $queries;
        $data['query_key'] = $queryKey;
        $data['sql']       = $sql;
        $data['results']   = $results;
        $data['columns']   = $columns;

        $this->view('layouts/header', ['title' => 'SQL Explorer']);
        $this->view('sql/index', $data);
        $this->view('layouts/footer');
    }
}
