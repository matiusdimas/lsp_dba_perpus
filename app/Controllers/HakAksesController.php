<?php

class HakAksesController extends Controller {

    public function __construct() {
        $this->requireRole('Administrator');
    }

    public function index() {
        $this->view('layouts/header', ['title' => 'Hak Akses Basis Data']);
        $this->view('hak_akses/index');
        $this->view('layouts/footer');
    }
}
