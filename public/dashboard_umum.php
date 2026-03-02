<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Umum - Sekaa Truna Truni</title>
    <link rel="stylesheet" href="../asset/css/dashboard_umum.css">
</head>

<body>

    <!-- ================= NAVBAR ================= -->
    <div class="navbar">
        <div class="navbar-title">
            Sekaa Truna Truni
        </div>

        <!-- Tombol untuk membuka popup login -->
        <button class="login-btn" onclick="openLogin()">
            Masuk
        </button>
    </div>


    <!-- ================= KONTEN UTAMA ================= -->
    <div class="container">
        <div class="card">
            <h1>Selamat Datang</h1>
            <p>Website Resmi Sekaa Truna Truni</p>
            <p>Silakan login untuk mengakses dashboard anggota atau pengurus.</p>
        </div>
    </div>


    <!-- ================= OVERLAY (LATAR BELAKANG GELAP) ================= -->
    <div id="overlay" class="overlay" onclick="closeLogin()"></div>


    <!-- ================= POPUP LOGIN ================= -->
    <div id="loginPopup" class="login-container">

        <!-- Tombol Tutup -->
        <span class="close-btn" onclick="closeLogin()">&times;</span>

        <h2>Masuk ke Sistem STT</h2>

        <?php
        // Menampilkan pesan error jika ada
        if (isset($_SESSION['error'])) {
            echo "<p class='error'>" . htmlspecialchars($_SESSION['error']) . "</p>";
            unset($_SESSION['error']);
        }
        ?>

        <!-- Form Login -->
        <form action="../src/controller/AuthController.php" method="POST">

            <label>Nama Pengguna</label>
            <input type="text" name="nama_pengguna" placeholder="Masukkan nama pengguna" required>

            <label>Kata Sandi</label>
            <input type="password" name="kata_sandi" placeholder="Masukkan kata sandi" required>

            <button type="submit">Masuk</button>

        </form>
    </div>


    <!-- ================= SCRIPT POPUP ================= -->
    <script>
    // Membuka popup login
    function openLogin() {
        document.getElementById("loginPopup").style.display = "block";
        document.getElementById("overlay").style.display = "block";
    }

    // Menutup popup login
    function closeLogin() {
        document.getElementById("loginPopup").style.display = "none";
        document.getElementById("overlay").style.display = "none";
    }
    </script>

</body>

</html>