<?php

class BukuController extends Controller {

    public function __construct() {
        $this->requireRole(['Administrator', 'Petugas', 'Anggota']);
    }

    public function index() {
        $bukuModel = $this->model('BukuModel');
        $keyword = $_GET['search'] ?? '';
        
        $data['buku']    = $bukuModel->getAllBuku($keyword);
        $data['keyword'] = $keyword;

        $this->view('layouts/header', ['title' => 'Data Buku']);
        $this->view('buku/index', $data);
        $this->view('layouts/footer');
    }

    public function tambah() {
        $this->requireRole(['Administrator', 'Petugas']);
        $bukuModel = $this->model('BukuModel');
        $data['kategori']  = $bukuModel->getAllKategori();
        $data['next_id']   = $bukuModel->getNextId();

        $this->view('layouts/header', ['title' => 'Tambah Buku']);
        $this->view('buku/form', $data);
        $this->view('layouts/footer');
    }

    public function simpan() {
        $this->requireRole(['Administrator', 'Petugas']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?url=buku/index');
        }

        $bukuModel = $this->model('BukuModel');
        $bukuModel->create([
            'id_buku'     => $_POST['id_buku'],
            'id_kategori' => $_POST['id_kategori'],
            'judul'       => $_POST['judul'],
            'penulis'     => $_POST['penulis'],
            'penerbit'    => $_POST['penerbit'],
            'stok'        => (int)$_POST['stok'],
        ]);
        $_SESSION['flash'] = "Buku '{$_POST['judul']}' berhasil ditambahkan.";
        $this->redirect('index.php?url=buku/index');
    }

    public function edit($id = '') {
        $this->requireRole(['Administrator', 'Petugas']);
        $bukuModel = $this->model('BukuModel');
        $data['buku']     = $bukuModel->find($id);
        $data['kategori'] = $bukuModel->getAllKategori();

        if (!$data['buku']) {
            $_SESSION['error'] = "Buku tidak ditemukan.";
            $this->redirect('index.php?url=buku/index');
        }

        $this->view('layouts/header', ['title' => 'Edit Buku']);
        $this->view('buku/form', $data);
        $this->view('layouts/footer');
    }

    public function update($id = '') {
        $this->requireRole(['Administrator', 'Petugas']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?url=buku/index');
        }

        $bukuModel = $this->model('BukuModel');
        $bukuModel->update($id, [
            'id_kategori' => $_POST['id_kategori'],
            'judul'       => $_POST['judul'],
            'penulis'     => $_POST['penulis'],
            'penerbit'    => $_POST['penerbit'],
            'stok'        => (int)$_POST['stok'],
        ]);
        $_SESSION['flash'] = "Buku '{$_POST['judul']}' berhasil diperbarui.";
        $this->redirect('index.php?url=buku/index');
    }

    public function hapus($id = '') {
        $this->requireRole('Administrator');
        $bukuModel = $this->model('BukuModel');
        $bukuModel->delete($id);
        $_SESSION['flash'] = "Buku berhasil dihapus.";
        $this->redirect('index.php?url=buku/index');
    }

    public function fixStok() {
        $this->requireRole('Administrator');
        $bukuModel = $this->model('BukuModel');
        $bukuModel->fixStokNegatif();
        $_SESSION['flash'] = "Stok tidak valid (negatif) berhasil diperbaiki menjadi 0.";
        $this->redirect('index.php?url=buku/index');
    }
}
