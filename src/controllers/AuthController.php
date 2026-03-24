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

            $user = $this->model->verifyLogin($nama_pengguna,$kata_sandi);

            if($user){
            // Login berhasil
            $_SESSION['user'] = $user;
            $_SESSION['flash_message'] = "Selamat datang, " . htmlspecialchars($user['nama_lengkap']);

            // Redirect sesuai jabatan
            if($user['jabatan'] == "anggota"){
                header("Location: ../../public/dashboard_anggota.php");
            } else {
                header("Location: ../../public/dashboard_pengurus.php");
            }

            exit;

        } else {
            // Login gagal
            $_SESSION['login_error'] = "Nama pengguna atau kata sandi salah";
            header("Location: ../../public/dashboard_umum.php");
            exit;
            }
        }
    }

    public function logout(){

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