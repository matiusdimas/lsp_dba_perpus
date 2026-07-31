<?php

class HomeController extends Controller {

    public function __construct() {
        $this->checkAuth();
    }

    public function index() {
        $peminjamanModel   = $this->model('PeminjamanModel');
        $bukuModel         = $this->model('BukuModel');
        $anggotaModel      = $this->model('AnggotaModel');
        $pengembalianModel = $this->model('PengembalianModel');

        $user = $this->user();
        $idAnggota = ($user['role'] === 'Anggota') ? ($user['id_anggota'] ?? null) : null;

        $data['peminjaman_aktif']   = $peminjamanModel->getPeminjamanAktif($idAnggota);
        $data['total_buku']         = count($bukuModel->getAllBuku());
        $data['total_anggota']      = count($anggotaModel->getAllAnggota());
        $data['total_pengembalian'] = count($pengembalianModel->getAllPengembalian($idAnggota));

        $this->view('layouts/header', ['title' => 'Dashboard Perpustakaan']);
        $this->view('home/index', $data);
        $this->view('layouts/footer');
    }
}
