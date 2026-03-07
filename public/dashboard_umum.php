<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Umum - Sekaa Truna Truni</title>
    <link rel="stylesheet" href="../asset/css/DashboardUmum.css?v=<?php echo time(); ?>">
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