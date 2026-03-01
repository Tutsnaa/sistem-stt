<?php
require_once __DIR__ . '/../models/PenggunaModel.php';

class AuthController {

    private $penggunaModel;

    public function __construct() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->penggunaModel = new PenggunaModel();
    }

    // ===============================
    // PROSES LOGIN
    // ===============================
    public function login() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $nama_pengguna = trim($_POST['nama_pengguna']);
            $kata_sandi    = trim($_POST['kata_sandi']);

            if (empty($nama_pengguna) || empty($kata_sandi)) {
                $_SESSION['error'] = "Nama Pengguna dan Kata Sandi wajib diisi!";
                header("Location: ../../public/login.php");
                exit;
            }

            $user = $this->penggunaModel->verifyLogin($nama_pengguna, $kata_sandi);

            if ($user) {

                // ⚠️ Jika kamu pakai status 'dibuka'
                if ($user['status'] !== 'aktif') {
                    $_SESSION['error'] = "Akun tidak aktif!";
                    header("Location: ../../public/login.php");
                    exit;
                }

                session_regenerate_id(true);

                $_SESSION['user'] = [
                    'id_pengguna'  => $user['id_pengguna'],
                    'nama_lengkap' => $user['nama_lengkap'],
                    'jabatan'      => $user['jabatan'],
                    'foto'         => $user['foto']
                ];

                // Redirect berdasarkan jabatan
                if ($user['jabatan'] === 'anggota') {
                    header("Location: ../../public/dashboard_anggota.php");
                } else {
                    header("Location: ../../public/dashboard_pengurus.php");
                }

                exit;

            } else {

                $_SESSION['error'] = "Nama Pengguna atau Kata Sandi salah!";
                header("Location: ../../public/login.php");
                exit;
            }
        }
    }

    // ===============================
    // LOGOUT
    // ===============================
    public function logout() {

        $_SESSION = [];
        session_destroy();

        header("Location: ../../public/login.php");
        exit;
    }
}

// Jalankan otomatis saat file dipanggil dari form
$auth = new AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth->login();
}