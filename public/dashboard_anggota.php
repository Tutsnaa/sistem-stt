<?php
session_start();

// Cek login & jabatan anggota
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
    <link rel="stylesheet" href="../asset/css/DashboardAnggota.css">
</head>

<body class="body-bg">

    <!-- ================= NAVBAR ================= -->
    <div class="navbar">
        <div class="navbar-title">
            Sekaa Truna Truni
        </div>

        <div class="navbar-user">
            <?= htmlspecialchars($user['nama_lengkap']) ?> (Anggota)

            <form method="POST" action="dashboard_umum.php" style="display:inline;">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

    <!-- ================= KONTEN UTAMA ================= -->
    <div class="hero-section">
        <div class="hero-container">

            <!-- KIRI : TEKS -->
            <div class="hero-text">
                <h1>Selamat Datang, <?= htmlspecialchars($user['nama_lengkap']) ?></h1>
                <h3>Dashboard Anggota Sekaa Truna Truni</h3>

                <p>
                    Anda dapat melihat informasi kegiatan terbaru, pengumuman organisasi,
                    serta status administrasi Anda melalui dashboard ini.
                    Tetap aktif berpartisipasi dalam setiap kegiatan Sekaa Truna Truni.
                </p>

                <a href="#" class="btn-primary">
                    Lihat Kegiatan
                </a>
            </div>

            <!-- KANAN : GAMBAR -->
            <div class="hero-image">
                <div class="image-blob">
                    <img src="../asset/img/Gambar.png" alt="Ilustrasi STT">
                </div>
            </div>

        </div>
    </div>

</body>

</html>