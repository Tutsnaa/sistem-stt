<?php
session_start();
// ================================
// NOTIFIKASI
// ================================
require_once __DIR__ . '/../src/view/flash_message.php';

// Cek login & pastikan sebagai anggota
if (!isset($_SESSION['user']) || $_SESSION['user']['jabatan'] !== 'anggota') {
    header("Location: dashboard_umum.php");
    exit();
}

$user = $_SESSION['user'];
$page = $_GET['page'] ?? 'dashboard';

// panggil model untuk menampilkan data pengguna
require_once __DIR__ . '/../src/models/PenggunaModel.php';


$model = new PenggunaModel();

// ambil kata pencarian
$search = isset($_GET['search']) ? $_GET['search'] : null;

// ambil data anggota
$dataAnggota = $model->getAll($search);

require_once __DIR__ . '/../src/models/KeuanganModel.php';

$keuanganModel = new KeuanganModel();

$totalPemasukan = $keuanganModel->getTotalPemasukan();
$totalPengeluaran = $keuanganModel->getTotalPengeluaran();
$uangKas = $totalPemasukan - $totalPengeluaran;

require_once __DIR__ . '/../src/models/PengumumanModel.php';

$pengumumanModel = new PengumumanModel();

// ambil jabatan user login
$jabatan = $_SESSION['user']['jabatan'];

// ambil data pengumuman
$dataPengumuman = $pengumumanModel->getAllPengumuman($jabatan);

require_once __DIR__ . '/../src/models/VotingModel.php';
require_once __DIR__ . '/../src/models/KandidatModel.php';


$votingModel = new VotingModel();
$votingModel->autoUpdateStatus();
$kandidatModel = new KandidatModel();

// Ambil semua voting
$votingList = array_filter($votingModel->getAllVoting(), function($v){
    return $v['status'] === 'dibuka';
});


// Ambil semua kandidat
$kandidat = $kandidatModel->getAllKandidat();

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Sekaa Truna Truni</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../asset/css/DashboardAnggota.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/PagesAnggotaRoleA.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/PagesKeuanganRoleA.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/PagesDashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/PagesProfilAnggota.css?v=<?php echo time(); ?>">
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
                <li><a href="dashboard_anggota.php?page=home"
                        class="<?php echo ($page == 'home' && !isset($_GET['section'])) ? 'active' : ''; ?>">
                        Beranda
                    </a></li>
                <li><a href="dashboard_anggota.php?page=home&section=pengumuman#pengumuman"
                        class="<?php echo (isset($_GET['section']) && $_GET['section'] == 'pengumuman') ? 'active' : ''; ?>">
                        Pengumuman
                    </a></li>
                <li><a href="dashboard_anggota.php?page=anggota"
                        class="<?php echo ($page == 'anggota') ? 'active' : ''; ?>">
                        Data Anggota
                    </a></li>
                <li><a href="dashboard_anggota.php?page=keuangan"
                        class="<?php echo ($page == 'keuangan') ? 'active' : ''; ?>">
                        Keuangan
                    </a></li>
                <li><a href="dashboard_anggota.php?page=voting_anggota"
                        class="<?php echo ($page == 'voting_anggota') ? 'active' : ''; ?>">
                        voting
                    </a></li>
                <li><a href="dashboard_anggota.php?page=home&section=kontak#kontak"
                        class="<?php echo (isset($_GET['section']) && $_GET['section'] == 'kontak') ? 'active' : ''; ?>">
                        Kontak
                    </a></li>

                <li>
                    <a href="dashboard_anggota.php?page=profil"
                        class="<?php echo ($page == 'profil') ? 'active' : ''; ?>">
                        Profil
                    </a>
                </li>
            </ul>

        </div>
        <div class="navbar-user">
            <img src="../uploads/<?php echo $user['foto']; ?>" class="user-photo">
            <span><?php echo $user['nama_lengkap']; ?></span>
            <form method="POST" action="../src/controllers/AuthController.php?action=logout" class="logout-form">
                <button type="submit" class="logout-btn">
                    <i class="fa-solid fa-right-from-bracket"></i> Keluar
                </button>
            </form>
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

    case 'voting_anggota':
        include '../src/pages/voting_anggota.php';
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