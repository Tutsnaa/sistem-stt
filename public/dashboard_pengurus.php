<?php
session_start();

// ================================
// NOTIFIKASI
// ================================
require_once __DIR__ . '/../src/view/flash_message.php';

// ================================
// CEK LOGIN & PASTIKAN BUKAN ANGGOTA
// ================================
if (!isset($_SESSION['user']) || $_SESSION['user']['jabatan'] === 'anggota') {
    header("Location: dashboard_umum.php");
    exit();
}

$user = $_SESSION['user'];
$page = $_GET['page'] ?? 'home_pengurus';
$bulan = $_GET['bulan'] ?? null;
$tahun = $_GET['tahun'] ?? date('Y');

// ================================
// LOAD MODEL PENGGUNA
// ================================
require_once __DIR__ . '/../src/models/PenggunaModel.php';
$penggunaModel = new PenggunaModel();
$search = $_GET['search'] ?? null;
$dataAnggota = $penggunaModel->getAll($search);

// ================================
// LOAD MODEL KEUANGAN
// ================================
require_once __DIR__ . '/../src/models/KeuanganModel.php';
$keuanganModel = new KeuanganModel();
$totalPemasukan = $keuanganModel->getTotalPemasukan($bulan, $tahun);
$totalPengeluaran = $keuanganModel->getTotalPengeluaran($bulan, $tahun);
$uangKas = $totalPemasukan - $totalPengeluaran;

// ================================
// LOAD MODEL PENGUMUMAN
// ================================
require_once __DIR__ . '/../src/models/PengumumanModel.php';
$pengumumanModel = new PengumumanModel();
$pengumuman = $pengumumanModel->getAllPengumuman();
$dataPengumuman = $pengumumanModel->getAllPengumuman();

// ================================
// LOAD MODEL VOTING & KANDIDAT
// ================================
require_once __DIR__ . '/../src/models/VotingModel.php';
require_once __DIR__ . '/../src/models/KandidatModel.php';

$votingModel = new VotingModel();
$votingModel->autoUpdateStatus();
$kandidatModel = new KandidatModel();

// Ambil semua voting
$voting = $votingModel->getAllVoting();

// Ambil semua kandidat
$kandidat = $kandidatModel->getAllKandidat();

// Ambil semua anggota (pengguna)
$anggota = $penggunaModel->getAll();

// ================================
// LOAD MODEL KEPENGURUSAN
// ================================
require_once __DIR__ . '/../src/models/KepengurusanModel.php';
$kepengurusanModel = new KepengurusanModel();
$kepengurusan = $kepengurusanModel->getAll(); // Pastikan ada method getAll()
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Sekaa Truna Truni</title>
    <link rel="stylesheet" href="../asset/css/DashboardPengurus.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/sidebar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/PagesDashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/PagesProfil.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/PagesAnggota.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/PagesKeuangan.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/PagesPengumuman.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/PagesVoting.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/PagesKepengurusan.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <!-- ================= NAVBAR ================= -->
    <div class="navbar">

        <div class="navbar-title">
            Sekaa Truna Truni
        </div>

        <div class="navbar-user">
            <img src="../uploads/<?php echo $user['foto']; ?>" class="user-photo">
            <span><?php echo $user['nama_lengkap']; ?></span>
        </div>
    </div>

    <!-- ================= LAYOUT ================= -->
    <div class="dashboard-wrapper">
        <!-- ===== SIDEBAR ===== -->
        <div class="sidebar">
            <h2>Sekaa Truna Truni</h2>
            <div class="sidebar-menu">
                <ul>
                    <!-- <h3 class="menu-title">Dashboard</h3> -->

                    <li>
                        <a href="dashboard_pengurus.php?page=home_pengurus"
                            class="<?php echo ($page == 'home_pengurus') ? 'active' : ''; ?>">
                            Home
                        </a>
                    </li>

                    <li>
                        <a href="dashboard_pengurus.php?page=pengumuman"
                            class="<?php echo ($page == 'pengumuman') ? 'active' : ''; ?>">
                            Pengumuman
                        </a>
                    </li>

                    <li>
                        <a href="dashboard_pengurus.php?page=profil"
                            class="<?php echo ($page == 'profil') ? 'active' : ''; ?>">
                            Profil
                        </a>
                    </li>

                    <li>
                        <a href="dashboard_pengurus.php?page=anggota"
                            class="<?php echo ($page == 'anggota') ? 'active' : ''; ?>">
                            Data Anggota
                        </a>
                    </li>

                    <li>
                        <a href="dashboard_pengurus.php?page=kepengurusan"
                            class="<?php echo ($page == 'kepengurusan') ? 'active' : ''; ?>">
                            Kepengurusan
                        </a>
                    </li>

                    <li>
                        <a href="dashboard_pengurus.php?page=keuangan"
                            class="<?php echo ($page == 'keuangan') ? 'active' : ''; ?>">
                            Keuangan
                        </a>
                    </li>

                    <li>
                        <a href="dashboard_pengurus.php?page=voting"
                            class="<?php echo ($page == 'voting') ? 'active' : ''; ?>">
                            Voting
                        </a>
                    </li>
                </ul>
            </div>

            <form method="POST" action="dashboard_umum.php" class="logout-form">
                <button class="logout-btn">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </form>

        </div>

        <!-- ===== MAIN CONTENT ===== -->
        <div class="main-content">

            <?php

switch ($page) {

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

    case 'pengumuman':
        include '../src/pages/pengumuman.php';
        break;

    case 'voting':
        include '../src/pages/voting.php';
        break;

    default:
        include '../src/pages/home_pengurus.php';
}
?>

        </div>

    </div>

</body>

</html>