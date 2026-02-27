<?php
require_once __DIR__ . '/../models/PenggunaModel.php';

class PenggunaController {

    private $model;

    // ===============================
    // Constructor (ambil model)
    // ===============================
    public function __construct() {
        $this->model = new PenggunaModel();
    }

    // ===============================
    // Tampilkan Semua Data Pengguna
    // ===============================
    public function index() {
        return $this->model->getAll();
    }

    // ===============================
    // Simpan Data Pengguna Baru
    // ===============================
    public function store() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $this->model->create($_POST);

            header("Location: pengguna.php");
            exit;
        }
    }

    // ===============================
    // Update Data Pengguna
    // ===============================
    public function update($id) {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $this->model->update($id, $_POST);

            header("Location: pengguna.php");
            exit;
        }
    }

    // ===============================
    // Hapus Data Pengguna
    // ===============================
    public function delete($id) {

        $this->model->delete($id);

        header("Location: pengguna.php");
        exit;
    }
}