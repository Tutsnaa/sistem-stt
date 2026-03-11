<?php
session_start();

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
$dataPengumuman = $pengumumanModel->getAllPengumuman();

?>

<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Umum - Sekaa Truna Truni</title>
    <link rel="stylesheet" href="../asset/css/DashboardAnggota.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/PagesDashboard.css?v=<?php echo time(); ?>">
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
                <li><a href="#home">Home</a></li>
                <li><a href="#pengumuman">Pengumuman</a></li>
                <li><a href="#anggota">Anggota</a></li>
                <li><a href="#keuangan">Keuangan</a></li>
                <li><a href="#voting">Voting</a></li>
            </ul>

        </div>
        <div class="navbar-user">
            <img src="../uploads/<?php echo $user['foto']; ?>" class="user-photo">
            <span><?php echo $user['nama_lengkap']; ?></span>
            <form method="POST" action="dashboard_umum.php" class="logout-form">
                <button class="logout-btn">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </form>
        </div>
    </div>


    <!-- ================= HOME ================= -->
    <div id="home" class="hero-section">
        <div class="hero-container">

            <div class="hero-text">

                <h1>Sekaa Truna Truni Putra Kencana</h1>
                <h3>Media Informasi, Kegiatan, dan Administrasi Organisasi</h3>

                <p>
                    Sekaa Truna Truni Putra Kencana yang berlokasi di Banjar Kawan, Mas,
                    Kabupaten Gianyar merupakan organisasi kepemudaan yang menjadi
                    wadah kebersamaan dan kreativitas generasi muda.
                </p>

            </div>

            <div class="hero-image">
                <div class="image-blob">
                    <img src="../asset/img/Gambar2.jpeg">
                </div>
            </div>

        </div>
    </div>

    <!-- ================= PENGUMUMAN ================= -->
    <div id="pengumuman" class="section-pengumuman">

        <h2>Pengumuman</h2>

        <div class="pengumuman-list">

            <?php if(!empty($dataPengumuman)): ?>
            <?php foreach($dataPengumuman as $p): ?>

            <?php if($p['status'] == 'tampil'): ?>

            <div class="pengumuman-card">

                <h3><?= htmlspecialchars($p['judul']) ?></h3>

                <p>
                    <?= nl2br(htmlspecialchars($p['isi'])) ?>
                </p>

                <?php if($p['file']): ?>
                <a href="../uploads/<?= $p['file'] ?>" target="_blank">
                    Download File
                </a>
                <?php endif; ?>

                <small>
                    Oleh: <?= $p['nama_lengkap'] ?> <br>
                    <?= date('d M Y', strtotime($p['tanggal_dibuat'])) ?>
                </small>

            </div>

            <?php endif; ?>

            <?php endforeach; ?>
            <?php else: ?>

            <p>Tidak ada pengumuman.</p>

            <?php endif; ?>

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