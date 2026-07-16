<?php
session_start();

// ================================
// NOTIFIKASI
// ================================
require_once __DIR__ . '/../src/view/flash_message.php';

$page = $_GET['page'] ?? 'home';

require_once __DIR__ . '/../src/models/PengumumanModel.php';

$pengumumanModel = new PengumumanModel();

// ambil jabatan user login
$jabatan = $_SESSION['user']['jabatan'] ?? 'anggota';

// ambil data pengumuman
$dataPengumuman = $pengumumanModel->getAllPengumuman($jabatan);

require_once __DIR__ . '/../src/models/PenggunaModel.php';

$model = new PenggunaModel();

// ambil keyword search
$search = $_GET['search'] ?? null;

// ambil data anggota
$dataAnggota = $model->getAll($search);

require_once '../src/models/AgendaModel.php';
require_once '../src/models/PengumumanModel.php';

$agendaModel = new AgendaModel();
$pengumumanModel = new PengumumanModel();

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Sekaa Truna Truni</title>
    <link rel="stylesheet" href="../asset/css/DashboardUmum.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/PagesDashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


    <!-- FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Dosis:wght@200..800&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="../asset/css/font.css?v=<?php echo time(); ?>">
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
                        Beranda
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

    case 'pengumuman':
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

    case 'voting':
        include '../src/pages/voting.php';
        break;
}
?>

    </div>


    <!-- ================= OVERLAY ================= -->
    <div id="overlay" class="overlay" onclick="closeLogin()"></div>

    <!-- ================= POPUP LOGIN ================= -->
    <div id="loginPopup" class="login-container">

        <span class="close-btn" onclick="closeLogin()">&times;</span>

        <h2>Masuk</h2>

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



</body>

</html>

<!-- ================= SCRIPT ================= -->
<script>
window.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        var successMsg = document.getElementById('flash-message');
        var errorMsg = document.getElementById('flash-error');

        if (successMsg) successMsg.style.display = 'none';
        if (errorMsg) errorMsg.style.display = 'none';
    }, 5000);
});

function openLogin() {
    document.getElementById("loginPopup").style.display = "block";
    document.getElementById("overlay").style.display = "block";
}

function closeLogin() {
    document.getElementById("loginPopup").style.display = "none";
    document.getElementById("overlay").style.display = "none";
}
</script>