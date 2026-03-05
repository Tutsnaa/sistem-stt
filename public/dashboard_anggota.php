<?php
session_start();

// Cek login & pastikan sebagai anggota
if (!isset($_SESSION['user']) || $_SESSION['user']['jabatan'] !== 'anggota') {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Anggota - STT</title>
    <link rel="stylesheet" href="../asset/css/DashboardAnggota.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/sidebar.css?v=<?php echo time(); ?>">
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

                    <li><a href="#" class="active">Dashboard</a></li>
                    <li><a href="#">Profil</a></li>
                    <li><a href="#">Kepengurusan</a></li>
                    <li><a href="#">Pemasukan</a></li>
                    <li><a href="#">Pengeluaran</a></li>
                    <li><a href="#">Pengumuman</a></li>
                    <li><a href="#">Voting</a></li>

                </ul>
            </div>

            <form method="POST" action="dashboard_umum.php" class="logout-form">
                <button class="logout-btn">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </form>

        </div>

        <!-- ===== MAIN CONTENT ===== -->
        <!-- <div class="main-content">

            <h2>Dashboard Pengurus</h2>
            <p>Selamat datang di sistem pengelolaan Sekaa Truna Truni.</p>

        </div> -->

    </div>

</body>

</html>