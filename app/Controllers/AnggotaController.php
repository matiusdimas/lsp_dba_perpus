<?php

class AnggotaController extends Controller {

    public function __construct() {
        $this->requireRole(['Administrator', 'Petugas']);
    }

    public function index() {
        $anggotaModel = $this->model('AnggotaModel');
        $keyword = $_GET['search'] ?? '';

        $data['anggota'] = $anggotaModel->getAllAnggota($keyword);
        $data['keyword'] = $keyword;
        $data['ganda']   = $anggotaModel->checkDataGanda();
        $data['invalid'] = $anggotaModel->checkDataInvalid();

        $this->view('layouts/header', ['title' => 'Data Anggota & Kualitas Data']);
        $this->view('anggota/index', $data);
        $this->view('layouts/footer');
    }

    public function tambah() {
        $this->requireRole('Administrator');
        $anggotaModel   = $this->model('AnggotaModel');
        $data['next_id'] = $anggotaModel->getNextId();

        $this->view('layouts/header', ['title' => 'Tambah Anggota']);
        $this->view('anggota/form', $data);
        $this->view('layouts/footer');
    }

    public function simpan() {
        $this->requireRole('Administrator');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?url=anggota/index');
        }
        $anggotaModel = $this->model('AnggotaModel');
        $anggotaModel->create([
            'id_anggota' => $_POST['id_anggota'],
            'nama'       => $_POST['nama'],
            'email'      => $_POST['email'],
            'no_hp'      => $_POST['no_hp'],
            'alamat'     => $_POST['alamat'],
        ]);
        $_SESSION['flash'] = "Anggota '{$_POST['nama']}' berhasil ditambahkan.";
        $this->redirect('index.php?url=anggota/index');
    }

    public function edit($id = '') {
        $this->requireRole('Administrator');
        $anggotaModel   = $this->model('AnggotaModel');
        $data['anggota'] = $anggotaModel->find($id);

        if (!$data['anggota']) {
            $_SESSION['error'] = "Anggota tidak ditemukan.";
            $this->redirect('index.php?url=anggota/index');
        }

        $this->view('layouts/header', ['title' => 'Edit Anggota']);
        $this->view('anggota/form', $data);
        $this->view('layouts/footer');
    }

    public function update($id = '') {
        $this->requireRole('Administrator');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?url=anggota/index');
        }
        $anggotaModel = $this->model('AnggotaModel');
        $anggotaModel->update($id, [
            'nama'   => $_POST['nama'],
            'email'  => $_POST['email'],
            'no_hp'  => $_POST['no_hp'],
            'alamat' => $_POST['alamat'],
        ]);
        $_SESSION['flash'] = "Anggota berhasil diperbarui.";
        $this->redirect('index.php?url=anggota/index');
    }

    public function hapus($id = '') {
        $this->requireRole('Administrator');
        $anggotaModel = $this->model('AnggotaModel');
        $anggotaModel->delete($id);
        $_SESSION['flash'] = "Anggota berhasil dihapus.";
        $this->redirect('index.php?url=anggota/index');
    }

    public function import() {
        $this->requireRole(['Administrator', 'Petugas']);
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file_csv'])) {
            $file = $_FILES['file_csv'];
            if ($file['error'] == UPLOAD_ERR_OK && strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)) == 'csv') {
                $anggotaModel  = $this->model('AnggotaModel');
                $successCount  = $anggotaModel->importCSV($file['tmp_name']);
                $_SESSION['flash'] = "Berhasil mengimpor {$successCount} data anggota dari CSV.";
            } else {
                $_SESSION['error'] = "File harus berformat CSV.";
            }
        }
        $this->redirect('index.php?url=anggota/index');
    }

    public function clean() {
        $this->requireRole('Administrator');
        $anggotaModel = $this->model('AnggotaModel');
        $anggotaModel->cleanDataGanda();
        $_SESSION['flash'] = "Data ganda berhasil dibersihkan.";
        $this->redirect('index.php?url=anggota/index');
    }
}
