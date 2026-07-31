<?php

class Controller {

    public function view($view, $data = []) {
        require_once __DIR__ . '/../Views/' . $view . '.php';
    }

    public function model($model) {
        require_once __DIR__ . '/../Models/' . $model . '.php';
        return new $model();
    }

    public function redirect($url) {
        header('Location: ' . $url);
        exit;
    }

    /**
     * Memastikan pengguna telah terautentikasi (Auth Guard).
     * Jika belum login, alihkan otomatis ke halaman login.
     */
    public function checkAuth() {
        if (!$this->isLoggedIn()) {
            $_SESSION['error'] = "Silakan login terlebih dahulu untuk mengakses sistem.";
            $this->redirect('index.php?url=auth/login');
        }
    }

    /**
     * Membatasi akses berdasarkan peran pengguna (RBAC Guard).
     */
    public function requireRole($roles) {
        $this->checkAuth();
        $allowed = is_array($roles) ? $roles : [$roles];
        if (!in_array($this->user()['role'], $allowed)) {
            $_SESSION['error'] = "Akses Ditolak: Peran '{$this->user()['role']}' tidak memiliki wewenang untuk fitur ini.";
            $this->redirect('index.php');
        }
    }

    public function isLoggedIn(): bool {
        return isset($_SESSION['user']) && !empty($_SESSION['user']);
    }

    public function user(): ?array {
        return $_SESSION['user'] ?? null;
    }

    public function hasRole(string $role): bool {
        return isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === $role;
    }
}
