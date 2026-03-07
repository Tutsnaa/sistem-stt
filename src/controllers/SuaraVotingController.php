<?php
require_once __DIR__ . '/../models/SuaraVotingModel.php';

class SuaraVotingController {

    private $model;

    // ===============================
    // Constructor (mulai session & model)
    // ===============================
    public function __construct() {
        session_start();
        $this->model = new SuaraVotingModel();
    }

    // ===============================
    // Proses Simpan Suara Voting
    // ===============================
    public function vote() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Ambil ID pengguna dari session
            $id_pengguna = $_SESSION['user']['id_pengguna'];

            // Ambil ID calon dari form
            $id_calon = $_POST['id_calon'];

            $this->model->vote($id_pengguna, $id_calon);

            header("Location: voting.php");
            exit;
        }
    }
}