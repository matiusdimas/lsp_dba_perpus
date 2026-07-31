<?php

class PengembalianController extends Controller {

    public function __construct() {
        $this->requireRole(['Administrator', 'Petugas', 'Anggota']);
    }

    public function index() {
        $pengembalianModel = $this->model('PengembalianModel');
        $user = $this->user();
        $idAnggota = ($user['role'] === 'Anggota') ? ($user['id_anggota'] ?? null) : null;
        $keyword = $_GET['search'] ?? '';

        $data['pengembalian'] = $pengembalianModel->getAllPengembalian($idAnggota, $keyword);
        $data['keyword'] = $keyword;

        $this->view('layouts/header', ['title' => 'Riwayat Pengembalian & Denda']);
        $this->view('pengembalian/index', $data);
        $this->view('layouts/footer');
    }

    public function tambah($id = '') {
        $this->requireRole(['Administrator', 'Petugas']);
        $selectedId = $id !== '' ? $id : ($_GET['id'] ?? '');

        $pengembalianModel = $this->model('PengembalianModel');
        $bukuModel         = $this->model('BukuModel');

        $data['peminjaman']  = $pengembalianModel->getPeminjamanAktifForReturn();
        $data['buku']        = $bukuModel->getAllBuku();
        $data['next_id']     = $pengembalianModel->getNextId();
        $data['selected_id'] = $selectedId;

        $this->view('layouts/header', ['title' => 'Catat Pengembalian']);
        $this->view('pengembalian/form', $data);
        $this->view('layouts/footer');
    }

    public function simpan() {
        $this->requireRole(['Administrator', 'Petugas']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?url=pengembalian/index');
        }

        $idPeminjaman = $_POST['id_peminjaman'];
        $tglKembali   = $_POST['tgl_kembali'];
        $idBuku       = $_POST['id_buku'];

        $pengembalianModel = $this->model('PengembalianModel');

        $db   = $pengembalianModel->getDb();
        $stmt = $db->prepare("SELECT tgl_jatuh_tempo FROM peminjaman WHERE id_peminjaman = ?");
        $stmt->execute([$idPeminjaman]);
        $pinjam = $stmt->fetch();

        $denda = $pengembalianModel->hitungDenda($pinjam['tgl_jatuh_tempo'] ?? date('Y-m-d'), $tglKembali);

        $result = $pengembalianModel->create([
            'id_pengembalian' => $pengembalianModel->getNextId(),
            'id_peminjaman'   => $idPeminjaman,
            'id_buku'         => $idBuku,
            'tgl_kembali'     => $tglKembali,
            'denda'           => $denda,
        ]);

        if ($result) {
            $dendaFmt = 'Rp ' . number_format($denda, 0, ',', '.');
            $_SESSION['flash'] = "Pengembalian berhasil dicatat. Denda: {$dendaFmt}";
        } else {
            $_SESSION['error'] = "Gagal mencatat pengembalian.";
        }
        $this->redirect('index.php?url=pengembalian/index');
    }
}
