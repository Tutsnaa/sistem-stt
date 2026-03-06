<?php
session_start();

// Cek login & pastikan bukan anggota
if (!isset($_SESSION['user']) || $_SESSION['user']['jabatan'] === 'anggota') {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Pengurus - STT</title>
    <link rel="stylesheet" href="../asset/css/DashboardPengurus.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/sidebar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/PagesDashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/PagesProfil.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <!-- ================= NAVBAR ================= -->
    <div class="navbar">

        <div class="navbar-title">
            Sekaa Truna Truni
        </div>

        <div class="navbar-user">
            <img src="../asset/img/<?php echo $user['foto']; ?>" class="user-photo">
            <span><?php echo $user['nama_lengkap']; ?></span>
        </div>
    </div>

    <!-- ================= LAYOUT ================= -->
    <div class="dashboard-wrapper">

        <!-- ===== SIDEBAR ===== -->
        <div class="sidebar">

            <div class="sidebar-menu">
                <ul>
                    <li><a href="dashboard_pengurus.php?page=dashboard" class="active">Dashboard</a></li>
                    <li><a href="dashboard_pengurus.php?page=profil">Profil</a></li>
                    <li><a href="dashboard_pengurus.php?page=anggota">Data Anggota</a></li>
                    <li><a href="dashboard_pengurus.php?page=kepengurusan">Kepengurusan</a></li>
                    <li><a href="dashboard_pengurus.php?page=pemasukan">Pemasukan</a></li>
                    <li><a href="dashboard_pengurus.php?page=pengeluaran">Pengeluaran</a></li>
                    <li><a href="dashboard_pengurus.php?page=pengumuman">Pengumuman</a></li>
                    <li><a href="dashboard_pengurus.php?page=voting">Voting</a></li>
                    <li><a href="dashboard_pengurus.php?page=laporan">Laporan</a></li>
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
$page = $_GET['page'] ?? 'dashboard';

switch ($page) {

    case 'profil':
        include '../src/pages/profil.php';
        break;

    case 'anggota':
        include '../src/pages/anggota.php';
        break;

    case 'kepengurusan':
        include '../src/pages/kepengurusan.php';
        break;

    case 'pemasukan':
        include '../src/pages/pemasukan.php';
        break;

    case 'pengeluaran':
        include '../src/pages/pengeluaran.php';
        break;

    case 'pengumuman':
        include '../src/pages/pengumuman.php';
        break;

    case 'voting':
        include '../src/pages/voting.php';
        break;

    case 'laporan':
        include '../src/pages/laporan.php';
        break;

    default:
        include '../src/pages/dashboard.php';
}
?>

        </div>

    </div>

</body>

</html>