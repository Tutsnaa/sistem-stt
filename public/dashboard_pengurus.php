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
    <link rel="stylesheet" href="../asset/css/DashboardPengurus.css">
</head>

<body class="body-bg">

    <!-- ================= NAVBAR ================= -->
    <div class="navbar">
        <div class="navbar-title">
            Sekaa Truna Truni
        </div>

        <div class="navbar-user">
            <?= htmlspecialchars($user['nama_lengkap']) ?>
            (<?= htmlspecialchars($user['jabatan']) ?>)

            <form method="POST" action="dashboard_umum.php" style="display:inline;">
                <button class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

    <!-- ================= LAYOUT ================= -->
    <div class="dashboard-container">

        <!-- ===== SIDEBAR ===== -->
        <div class="sidebar">
            <h3>Menu Pengurus</h3>
            <ul>
                <li><a href="#">Dashboard</a></li>
                <li><a href="#">Data Anggota</a></li>
                <li><a href="#">Kegiatan</a></li>
                <li><a href="#">Keuangan</a></li>
                <li><a href="#">Laporan</a></li>
            </ul>
        </div>

        <!-- ===== KONTEN ===== -->
        <div class="main-content">

            <h2>Selamat Datang, <?= htmlspecialchars($user['nama_lengkap']) ?></h2>
            <p>Kelola sistem Sekaa Truna Truni dengan lebih efisien dan terstruktur.</p>

            <div class="card-container">
                <div class="card">
                    <h3>Jumlah Anggota</h3>
                    <p>120</p>
                </div>

                <div class="card">
                    <h3>Kegiatan Aktif</h3>
                    <p>5</p>
                </div>

                <div class="card">
                    <h3>Saldo Kas</h3>
                    <p>Rp 5.000.000</p>
                </div>
            </div>

        </div>
    </div>

</body>

</html>