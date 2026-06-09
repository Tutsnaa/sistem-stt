<?php
session_start();
require_once __DIR__ . '/../models/PenggunaModel.php';

class AuthController {

    private $model;

    public function __construct(){
        $this->model = new PenggunaModel();
    }

   public function login(){

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $nama_pengguna = $_POST['nama_pengguna'];
        $kata_sandi    = $_POST['kata_sandi'];

        $result = $this->model->verifyLogin($nama_pengguna, $kata_sandi);

        // ================= LOGIN BERHASIL =================
        if($result['status'] == 'success'){

            $_SESSION['user'] = $result['data'];
            $_SESSION['flash_message'] = "Selamat datang, " . htmlspecialchars($result['data']['nama_lengkap']);
            $_SESSION['flash_type'] = "success";

            // redirect sesuai jabatan
            if($result['data']['jabatan'] == "anggota"){
                header("Location: ../../public/dashboard_anggota.php?page=home");
            } else {
                header("Location: ../../public/dashboard_pengurus.php?page=home_pengurus");
            }

            exit;
        }

        // ================= AKUN NONAKTIF =================
        elseif($result['status'] == 'nonaktif'){
            $_SESSION['flash_message'] = "Akun Anda nonaktif. Silakan hubungi pengurus.";
            $_SESSION['flash_type'] = "danger";
        }

        // ================= PASSWORD SALAH =================
        elseif($result['status'] == 'wrong_password'){
            $_SESSION['flash_message'] = "Kata sandi salah";
            $_SESSION['flash_type'] = "danger";
        }

        // ================= USERNAME TIDAK ADA =================
        else{
            $_SESSION['flash_message'] = "Nama pengguna tidak ditemukan";
            $_SESSION['flash_type'] = "danger";
        }

        // redirect balik ke halaman login
        header("Location: ../../public/dashboard_umum.php");
        exit;
    }
}

    public function logout(){

    $_SESSION = [];

    session_unset();
    session_destroy();

    header("Location: ../../public/dashboard_umum.php");
    exit;
}
}

$auth = new AuthController();

$action = $_GET['action'] ?? '';

if($action == "login"){
    $auth->login();
}

if($action == "logout"){
    $auth->logout();
}