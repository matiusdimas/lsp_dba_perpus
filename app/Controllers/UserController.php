<?php

class UserController extends Controller {

    public function __construct() {
        $this->requireRole('Administrator');
    }

    public function index() {
        $userModel = $this->model('UserModel');
        $keyword = $_GET['search'] ?? '';

        $data['users']   = $userModel->getAllUsers($keyword);
        $data['anggota'] = $userModel->getAnggotaTanpaAkun();
        $data['keyword'] = $keyword;

        $this->view('layouts/header', ['title' => 'Manajemen Akun']);
        $this->view('user/index', $data);
        $this->view('layouts/footer');
    }

    public function simpan() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?url=user/index');
        }

        $idAnggota = !empty($_POST['id_anggota']) ? $_POST['id_anggota'] : null;
        $userModel = $this->model('UserModel');

        // Validasi Relasi 1-to-1: 1 Anggota hanya boleh memiliki 1 Akun
        if ($idAnggota && $userModel->isAnggotaHasAccount($idAnggota)) {
            $_SESSION['error'] = "Gagal: Anggota ID {$idAnggota} sudah memiliki akun pengguna! Sesuai prinsip relasi 1-to-1, 1 anggota hanya boleh memiliki 1 akun.";
            $this->redirect('index.php?url=user/index');
        }

        $result = $userModel->create([
            'username'     => $_POST['username'],
            'password'     => $_POST['password'],
            'nama_lengkap' => $_POST['nama_lengkap'],
            'role'         => $_POST['role'],
            'id_anggota'   => $idAnggota,
        ]);

        if ($result) {
            $_SESSION['flash'] = "Akun pengguna '{$_POST['username']}' berhasil dibuat dengan relasi 1-to-1.";
        } else {
            $_SESSION['error'] = "Gagal membuat akun. Username sudah digunakan atau anggota sudah memiliki akun.";
        }
        $this->redirect('index.php?url=user/index');
    }

    public function edit($id = '') {
        $userModel = $this->model('UserModel');
        $user = $userModel->find($id);

        if (!$user) {
            $_SESSION['error'] = "Akun pengguna tidak ditemukan.";
            $this->redirect('index.php?url=user/index');
        }

        if ($user['username'] === 'admin') {
            $_SESSION['error'] = "Akun Superadmin tidak dapat diedit melalui antarmuka ini.";
            $this->redirect('index.php?url=user/index');
        }

        $data['user_edit'] = $user;
        $data['anggota']   = $userModel->getAnggotaTanpaAkun();

        // Include current anggota if linked so it appears in dropdown
        if ($user['id_anggota']) {
            $anggotaModel = $this->model('AnggotaModel');
            $currentAnggota = $anggotaModel->find($user['id_anggota']);
            if ($currentAnggota) {
                $data['anggota'][] = $currentAnggota;
            }
        }

        $this->view('layouts/header', ['title' => 'Edit Akun Pengguna']);
        $this->view('user/form', $data);
        $this->view('layouts/footer');
    }

    public function update($id = '') {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?url=user/index');
        }

        $userModel = $this->model('UserModel');
        
        $result = $userModel->update($id, [
            'username'     => $_POST['username'],
            'password'     => $_POST['password'], // Can be empty
            'nama_lengkap' => $_POST['nama_lengkap'],
            'role'         => $_POST['role'],
            'id_anggota'   => !empty($_POST['id_anggota']) ? $_POST['id_anggota'] : null,
        ]);

        if ($result) {
            $_SESSION['flash'] = "Akun pengguna berhasil diperbarui.";
        } else {
            $_SESSION['error'] = "Gagal memperbarui akun. Username sudah digunakan atau anggota sudah tertaut.";
        }
        $this->redirect('index.php?url=user/index');
    }

    public function toggleStatus($id = '') {
        $userModel = $this->model('UserModel');
        $user = $userModel->find($id);
        
        if ($user && $user['username'] === 'admin') {
            $_SESSION['error'] = "Akun Superadmin tidak dapat dinonaktifkan.";
        } else {
            $userModel->toggleStatus($id);
            $_SESSION['flash'] = "Status akun berhasil diubah.";
        }
        $this->redirect('index.php?url=user/index');
    }
}
