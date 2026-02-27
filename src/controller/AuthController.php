<?php
require_once __DIR__ . '/../models/PenggunaModel.php';

class AuthController {

    private $penggunaModel;

    public function __construct() {
        session_start();
        $this->penggunaModel = new PenggunaModel();
    }

    // ===============================
    // Proses Login
    // ===============================
    public function login() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = $this->penggunaModel->verifyLogin($username, $password);

            if ($user) {

                $_SESSION['user'] = $user;

                header("Location: dashboard.php");
                exit;

            } else {
                $_SESSION['error'] = "Username atau Password salah!";
                header("Location: login.php");
                exit;
            }
        }
    }

    // ===============================
    // Logout
    // ===============================
    public function logout() {
        session_destroy();
        header("Location: login.php");
        exit;
    }
}