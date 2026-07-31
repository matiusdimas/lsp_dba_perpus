<?php

class DokumenController extends Controller {

    public function __construct() {
        $this->requireRole(['Administrator', 'Petugas', 'Anggota']);
    }

    public function index() {
        $dokumenModel = $this->model('DokumenModel');
        $keyword = $_GET['search'] ?? '';
        
        $data['dokumen'] = $dokumenModel->getAllDokumen($keyword);
        $data['keyword'] = $keyword;

        $this->view('layouts/header', ['title' => 'Arsip Dokumen Pribadi']);
        $this->view('dokumen/index', $data);
        $this->view('layouts/footer');
    }

    public function tambah() {
        $this->requireRole(['Administrator', 'Petugas']);
        $this->view('layouts/header', ['title' => 'Tambah Dokumen']);
        $this->view('dokumen/form');
        $this->view('layouts/footer');
    }

    public function simpan() {
        $this->requireRole(['Administrator', 'Petugas']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?url=dokumen/index');
        }

        $dokumenModel = $this->model('DokumenModel');
        $dokumenModel->create([
            'judul_dokumen' => $_POST['judul_dokumen'],
            'nama_file'     => $_POST['nama_file'],
            'jenis_file'    => $_POST['jenis_file'],
            'lokasi_file'   => $_POST['lokasi_file'],
            'versi'         => $_POST['versi'] ?? '1.0',
        ]);
        $_SESSION['flash'] = "Dokumen '{$_POST['judul_dokumen']}' berhasil ditambahkan.";
        $this->redirect('index.php?url=dokumen/index');
    }

    public function edit($id = '') {
        $this->requireRole(['Administrator', 'Petugas']);
        $dokumenModel = $this->model('DokumenModel');
        $data['dokumen'] = $dokumenModel->find($id);

        if (!$data['dokumen']) {
            $_SESSION['error'] = "Dokumen tidak ditemukan.";
            $this->redirect('index.php?url=dokumen/index');
        }

        $this->view('layouts/header', ['title' => 'Edit Dokumen']);
        $this->view('dokumen/form', $data);
        $this->view('layouts/footer');
    }

    public function update($id = '') {
        $this->requireRole(['Administrator', 'Petugas']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?url=dokumen/index');
        }

        $dokumenModel = $this->model('DokumenModel');
        $dokumenModel->update($id, [
            'judul_dokumen' => $_POST['judul_dokumen'],
            'nama_file'     => $_POST['nama_file'],
            'jenis_file'    => $_POST['jenis_file'],
            'lokasi_file'   => $_POST['lokasi_file'],
            'versi'         => $_POST['versi'],
        ]);
        $_SESSION['flash'] = "Dokumen berhasil diperbarui.";
        $this->redirect('index.php?url=dokumen/index');
    }

    public function hapus($id = '') {
        $this->requireRole(['Administrator', 'Petugas']);
        $dokumenModel = $this->model('DokumenModel');
        $dokumenModel->delete($id);
        $_SESSION['flash'] = "Dokumen berhasil dihapus.";
        $this->redirect('index.php?url=dokumen/index');
    }
}
