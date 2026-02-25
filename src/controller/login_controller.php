<?php
session_start();

require_once __DIR__ . '/../../config/database.php';

class LoginController
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function login($username, $password)
    {
        $query = "SELECT * FROM users WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {

            // Verifikasi password hash
            if (password_verify($password, $user['password'])) {

                // Cek apakah user aktif
                if ($user['status'] !== 'aktif') {
                    echo "Akun tidak aktif!";
                    return;
                }

                // Simpan session
                $_SESSION['id_user']  = $user['id_user'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role']     = $user['role'];

                // Redirect berdasarkan role
                if ($user['role'] === 'Anggota') {
                    header("Location: ../../public/dashboard_anggota.php");
                } else {
                    header("Location: ../../public/dashboard_pengurus.php");
                }

                exit();

           } else {
        $_SESSION['error'] = "Password salah!";
        header("Location: ../../public/login.php");
        exit();
    }

} else {
    $_SESSION['error'] = "Username tidak ditemukan!";
    header("Location: ../../public/login.php");
    exit();
}
    }
}

// Jalankan login jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $controller = new LoginController();
        $controller->login($username, $password);
    } else {
        echo "Username dan Password wajib diisi!";
    }
}