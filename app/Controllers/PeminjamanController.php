<?php

class PeminjamanController extends Controller {

    public function __construct() {
        $this->requireRole(['Administrator', 'Petugas', 'Anggota']);
    }

    public function index() {
        $peminjamanModel = $this->model('PeminjamanModel');
        $user = $this->user();
        $idAnggota = ($user['role'] === 'Anggota') ? ($user['id_anggota'] ?? null) : null;
        $statusFilter = isset($_GET['status']) && $_GET['status'] !== '' ? $_GET['status'] : null;
        $keyword = $_GET['search'] ?? null;

        $data['peminjaman'] = $peminjamanModel->getAllPeminjaman($idAnggota, $statusFilter, $keyword);
        $data['status_filter'] = $statusFilter;
        $data['keyword'] = $keyword;

        $this->view('layouts/header', ['title' => 'Daftar Transaksi Peminjaman']);
        $this->view('peminjaman/index', $data);
        $this->view('layouts/footer');
    }

    public function tambah() {
        $this->requireRole(['Administrator', 'Petugas']);
        $anggotaModel    = $this->model('AnggotaModel');
        $bukuModel       = $this->model('BukuModel');
        $peminjamanModel = $this->model('PeminjamanModel');

        $data['anggota']  = $anggotaModel->getAllAnggota();
        $data['buku']     = $bukuModel->getAllBukuTersedia();
        $data['next_id']  = $peminjamanModel->getNextId();

        $this->view('layouts/header', ['title' => 'Catat Peminjaman Baru']);
        $this->view('peminjaman/form', $data);
        $this->view('layouts/footer');
    }

    public function simpan() {
        $this->requireRole(['Administrator', 'Petugas']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?url=peminjaman/index');
        }

        $peminjamanModel = $this->model('PeminjamanModel');
        $result = $peminjamanModel->create([
            'id_peminjaman'  => $_POST['id_peminjaman'],
            'id_anggota'     => $_POST['id_anggota'],
            'id_buku'        => $_POST['id_buku'],
            'tgl_pinjam'     => $_POST['tgl_pinjam'],
            'tgl_jatuh_tempo' => $_POST['tgl_jatuh_tempo'],
        ]);

        if ($result) {
            $_SESSION['flash'] = "Transaksi peminjaman berhasil dicatat.";
        } else {
            $_SESSION['error'] = "Gagal mencatat peminjaman. Pastikan stok buku tersedia.";
        }
        $this->redirect('index.php?url=peminjaman/index');
    }
}
