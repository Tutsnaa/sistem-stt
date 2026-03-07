<?php
require_once __DIR__ . '/../models/PengumumanModel.php';

class PengumumanController {

    private $model;

    // ===============================
    // Constructor
    // ===============================
    public function __construct() {
        session_start();
        $this->model = new PengumumanModel();
    }

    // ===============================
    // Tampilkan Semua Pengumuman
    // ===============================
    public function index() {
        return $this->model->getAll();
    }

    // ===============================
    // Tambah Pengumuman Baru
    // ===============================
    public function store() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = $_POST;
            $data['id_pengguna'] = $_SESSION['user']['id_pengguna'];

            $this->model->create($data);

            header("Location: pengumuman.php");
            exit;
        }
    }

    // ===============================
    // Hapus Pengumuman
    // ===============================
    public function delete($id) {

        $this->model->delete($id);

        header("Location: pengumuman.php");
        exit;
    }
}