<?php
session_start();
$page = $_GET['page'] ?? 'home';
require_once __DIR__ . '/../src/models/PengumumanModel.php';

$pengumumanModel = new PengumumanModel();
$dataPengumuman = $pengumumanModel->getAllPengumuman();

require_once __DIR__ . '/../src/models/PenggunaModel.php';


$model = new PenggunaModel();

// ambil data anggota
$dataAnggota = $model->getAll($search);

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Umum - Sekaa Truna Truni</title>
    <link rel="stylesheet" href="../asset/css/DashboardUmum.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/DashboardAnggota.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/PagesDashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/navbar.css?v=<?php echo time(); ?>">
</head>

<body class="body-bg">

    <!-- ================= NAVBAR ================= -->
    <div class="navbar">

        <div class="navbar-title">
            Sekaa Truna Truni
        </div>

        <div class="navbar-right">

            <ul class="navbar-menu">
                <li><a href="dashboard_umum.php?page=home"
                        class="<?php echo ($page == 'home' && !isset($_GET['section'])) ? 'active' : ''; ?>">
                        Home
                    </a></li>
                <li><a href="dashboard_umum.php?page=home&section=pengumuman#pengumuman"
                        class="<?php echo (isset($_GET['section']) && $_GET['section'] == 'pengumuman') ? 'active' : ''; ?>">
                        Pengumuman
                    </a></li>
                <li><a onclick="openLogin()" href="#">Data Anggota</a></li>
                <li><a onclick="openLogin()" href="#">Keuangan</a></li>
                <li><a onclick="openLogin()" href="#">Voting</a></li>
                <li><a href="dashboard_umum.php?page=home&section=kontak#kontak"
                        class="<?php echo (isset($_GET['section']) && $_GET['section'] == 'kontak') ? 'active' : ''; ?>">
                        Kontak
                    </a></li>
            </ul>

            <button class="login-btn" onclick="openLogin()">
                Masuk
            </button>

        </div>

    </div>

    <!-- ================= HERO ================= -->
    <!-- <div class="hero-section">
        <div class="hero-container">

            <div class="hero-text">

                <h1>Sekaa Truna Truni Galuh Mantri</h1>
                <h3>Media Informasi, Kegiatan, dan Administrasi Organisasi</h3>

                <p>
                    Sekaa Truna Truni Putra Kencana yang berlokasi di Banjar Kawan, Mas,
                    Kabupaten Gianyar merupakan organisasi kepemudaan yang menjadi
                    wadah kebersamaan dan kreativitas generasi muda.
                </p>

                <button class="btn-primary" onclick="openLogin()">
                    Masuk ke Sistem
                </button>

            </div>

            <div class="hero-image">
                <div class="image-blob">
                    <img src="../asset/img/LogoSTT.jpeg">
                </div>
            </div>

        </div>
    </div> -->


    <div>

        <?php
$page = $_GET['page'] ?? 'home';

switch ($page) {

    case 'home':
        include '../src/pages/home.php';
        break;

    case 'profil':
        include '../src/pages/profil.php';
        break;

    case 'profil':
        include '../src/pages/pengumuman.php';
        break;

    case 'anggota':
        include '../src/pages/anggota.php';
        break;

    case 'kepengurusan':
        include '../src/pages/kepengurusan.php';
        break;

    case 'keuangan':
        include '../src/pages/keuangan.php';
        break;

    case 'pengumuman':
        include '../src/pages/pengumuman.php';
        break;

    case 'voting':
        include '../src/pages/voting.php';
        break;

    // default:
    //     include '../src/pages/dashboard.php';
}
?>

    </div>


    <!-- ================= OVERLAY ================= -->
    <div id="overlay" class="overlay" onclick="closeLogin()"></div>

    <!-- ================= POPUP LOGIN ================= -->
    <div id="loginPopup" class="login-container">

        <span class="close-btn" onclick="closeLogin()">&times;</span>

        <h2>Masuk ke Sistem STT</h2>

        <?php
if(isset($_SESSION['error'])){
echo "<p class='error'>".$_SESSION['error']."</p>";
unset($_SESSION['error']);

echo "<script>
window.onload = function(){
openLogin();
}
</script>";
}
?>

        <!-- ================= FORM LOGIN ================= -->
        <form action="../src/controllers/AuthController.php?action=login" method="POST">

            <label>Nama Pengguna</label>
            <input type="text" name="nama_pengguna" required>

            <label>Kata Sandi</label>
            <input type="password" name="kata_sandi" required>

            <button type="submit">Masuk</button>

        </form>

    </div>

    <!-- ================= SCRIPT ================= -->
    <script>
    function openLogin() {
        document.getElementById("loginPopup").style.display = "block";
        document.getElementById("overlay").style.display = "block";
    }

    function closeLogin() {
        document.getElementById("loginPopup").style.display = "none";
        document.getElementById("overlay").style.display = "none";
    }
    </script>

</body>

</html>