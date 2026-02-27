<?php
require_once __DIR__ . '/../models/VotingModel.php';

class VotingController {

    private $model;

    // ===============================
    // Constructor
    // ===============================
    public function __construct() {
        session_start();
        $this->model = new VotingModel();
    }

    // ===============================
    // Tampilkan Semua Voting
    // ===============================
    public function index() {
        return $this->model->getAll();
    }

    // ===============================
    // Tambah Voting Baru
    // ===============================
    public function store() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = $_POST;
            $data['id_pengguna'] = $_SESSION['user']['id_pengguna'];

            $this->model->create($data);

            header("Location: voting.php");
            exit;
        }
    }

    // ===============================
    // Tutup Voting
    // ===============================
    public function tutup($id) {

        $this->model->tutupVoting($id);

        header("Location: voting.php");
        exit;
    }
}