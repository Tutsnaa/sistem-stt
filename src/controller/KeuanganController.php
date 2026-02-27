<?php
require_once __DIR__ . '/../models/KeuanganModel.php';

class KeuanganController {

    private $model;

    // ===============================
    // Constructor (Session & Model)
    // ===============================
    public function __construct() {

        session_start();

        // Cek login
        if (!isset($_SESSION['user'])) {
            header("Location: login.php");
            exit;
        }

        $this->model = new KeuanganModel();
    }

    // ===============================
    // Tampilkan semua data
    // ===============================
    public function index() {
        return $this->model->getAll();
    }

    // ===============================
    // Simpan data keuangan baru
    // ===============================
    public function store() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = $_POST;

            // ===============================
            // Upload file bukti
            // ===============================
            if (!empty($_FILES['file_bukti']['name'])) {

                $namaFile = time() . "_" . $_FILES['file_bukti']['name'];
                $tmpFile = $_FILES['file_bukti']['tmp_name'];
                $folder = "uploads/";

                // Buat folder jika belum ada
                if (!is_dir($folder)) {
                    mkdir($folder, 0777, true);
                }

                move_uploaded_file($tmpFile, $folder . $namaFile);

                $data['file_bukti'] = $namaFile;

            } else {
                $data['file_bukti'] = null;
            }

            // Ambil ID pengguna dari session
            $data['id_pengguna'] = $_SESSION['user']['id_pengguna'];

            $this->model->create($data);

            header("Location: keuangan.php");
            exit;
        }
    }

    // ===============================
    // Hapus data keuangan
    // ===============================
    public function delete($id) {

        $this->model->delete($id);

        header("Location: keuangan.php");
        exit;
    }
}