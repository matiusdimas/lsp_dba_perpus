<?php

class AuthController extends Controller {

    public function login() {
        if (isset($_SESSION['user'])) {
            $this->redirect('index.php');
        }

        $this->view('auth/login', ['title' => 'Login — Sistem Perpustakaan']);
    }

    public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?url=auth/login');
        }

        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($username) || empty($password)) {
            $_SESSION['error'] = "Username dan password wajib diisi.";
            $this->redirect('index.php?url=auth/login');
        }

        $userModel = $this->model('UserModel');
        $user = $userModel->authenticate($username, $password);

        if ($user) {
            $_SESSION['user']  = $user;
            $_SESSION['flash'] = "Selamat datang kembali, {$user['nama_lengkap']}!";
            $this->redirect('index.php');
        } else {
            $_SESSION['error'] = "Username atau password salah.";
            $this->redirect('index.php?url=auth/login');
        }
    }

    public function logout() {
        unset($_SESSION['user']);
        session_destroy();
        session_start();
        $_SESSION['flash'] = "Anda telah berhasil keluar (logout).";
        $this->redirect('index.php?url=auth/login');
    }
}
