<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Umum - Sekaa Truna Truni</title>
    <link rel="stylesheet" href="../asset/css/DashboardUmum.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/PagesDashboard.css?v=<?php echo time(); ?>">
</head>

<body class="body-bg">

    <!-- ================= NAVBAR ================= -->
    <div class="navbar">

        <div class="navbar-title">
            Sekaa Truna Truni
        </div>

        <div class="navbar-right">

            <ul class="navbar-menu">
                <li><a href="#">Kepengurusan</a></li>
                <li><a href="#">Pengumuman</a></li>
                <li><a href="#">Keuangan</a></li>
                <li><a href="#">Voting</a></li>
            </ul>

            <button class="login-btn" onclick="openLogin()">
                Masuk
            </button>

        </div>

    </div>

    <!-- ================= HERO ================= -->
    <div class="hero-section">
        <div class="hero-container">

            <div class="hero-text">

                <h1>Sekaa Truna Truni Putra Kencana</h1>
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
                    <img src="../asset/img/Gambar2.jpeg">
                </div>
            </div>

        </div>
    </div>

    <!-- ===== STRUKTUR ORGANISASI ===== -->
    <div class="struktur-wrapper">

        <h2 class="struktur-title">Struktur Organisasi</h2>

        <div class="org-container">

            <!-- Ketua -->
            <div class="org-row center">
                <div class="org-card">
                    <img src="../asset/img/ketua.jpg" alt="Ketua">
                    <h3>Nama Ketua</h3>
                    <p>Ketua</p>
                </div>
            </div>

            <!-- Garis vertikal ke Wakil -->
            <div class="org-line"></div>

            <!-- Wakil -->
            <div class="org-row center">
                <div class="org-card">
                    <img src="../asset/img/ketua.jpg" alt="Wakil">
                    <h3>Nama Wakil</h3>
                    <p>Wakil Ketua</p>
                </div>
            </div>

            <!-- Garis vertikal ke Sekretaris & Bendahara -->
            <div class="org-line"></div>

            <!-- Sekretaris & Bendahara -->
            <div class="org-row">
                <div class="org-card">
                    <img src="../asset/img/ketua.jpg" alt="Sekretaris1">
                    <h3>Sekretaris 1</h3>
                    <p>Sekretaris</p>
                </div>

                <div class="org-card">
                    <img src="../asset/img/ketua.jpg" alt="Sekretaris2">
                    <h3>Sekretaris 2</h3>
                    <p>Sekretaris</p>
                </div>

                <div class="org-card">
                    <img src="../asset/img/ketua.jpg" alt="Bendahara1">
                    <h3>Bendahara 1</h3>
                    <p>Bendahara</p>
                </div>

                <div class="org-card">
                    <img src="../asset/img/ketua.jpg" alt="Bendahara2">
                    <h3>Bendahara 2</h3>
                    <p>Bendahara</p>
                </div>
            </div>

        </div>

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