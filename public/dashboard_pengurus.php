<?php
session_start();
require_once __DIR__ . '/../src/view/confirm_message.php';
// ================================
// FLASH MESSAGE
// ================================
require_once __DIR__ . '/../src/view/flash_message.php';

// ================================
// CEK LOGIN
// ================================
if (
    !isset($_SESSION['user']) ||
    $_SESSION['user']['jabatan'] === 'anggota'
) {
    header("Location: dashboard_umum.php");
    exit();
}

// ================================
// DATA USER & FILTER
// ================================
$user   = $_SESSION['user'];
$jabatan = $user['jabatan'];

$page   = $_GET['page'] ?? 'home_pengurus';
$bulan  = $_GET['bulan'] ?? null;
$tahun  = $_GET['tahun'] ?? date('Y');
$search = $_GET['search'] ?? null;

// ================================
// LOAD MODEL
// ================================
require_once __DIR__ . '/../src/models/PenggunaModel.php';
require_once __DIR__ . '/../src/models/KeuanganModel.php';
require_once __DIR__ . '/../src/models/PengumumanModel.php';
require_once __DIR__ . '/../src/models/AgendaModel.php';
require_once __DIR__ . '/../src/models/KandidatModel.php';
require_once __DIR__ . '/../src/models/KepengurusanModel.php';

// ================================
// LOAD CONTROLLER VOTING
// ================================
require_once __DIR__ . '/../src/controllers/AgendaController.php';

// ================================
// INISIALISASI MODEL
// ================================
$penggunaModel      = new PenggunaModel();
$keuanganModel      = new KeuanganModel();
$pengumumanModel    = new PengumumanModel();
$kandidatModel      = new KandidatModel();
$kepengurusanModel  = new KepengurusanModel();

// ================================
// DATA ANGGOTA
// ================================
$dataAnggota = $penggunaModel->getAll($search);
$anggota     = $penggunaModel->getAll();

// ================================
// DATA KEUANGAN
// ================================
$totalPemasukan   = $keuanganModel->getTotalPemasukan($bulan, $tahun);
$totalPengeluaran = $keuanganModel->getTotalPengeluaran($bulan, $tahun);
$uangKas          = $totalPemasukan - $totalPengeluaran;

// ================================
// DATA PENGUMUMAN
// ================================
$pengumuman      = $pengumumanModel->getAllPengumuman($jabatan);
$dataPengumuman  = $pengumuman;

// ================================
// AUTO UPDATE STATUS agenda
// ================================
$agendaModel->autoUpdateStatus();

// ================================
// DATA agenda
// ================================
$agenda  = $agendaModel->getAllAgenda();
$agendas = $agendaModel->getAllAgenda();

// ================================
// AMBIL KANDIDAT PER agenda
// ================================
foreach ($agendas as &$v) {

    $v['calon'] = $agendaModel->getCalonByAgenda(
        $v['id_agenda']
    );
}
unset($v);

// ================================
// DATA KANDIDAT
// ================================
$kandidat = $kandidatModel->getAllKandidat();

// ================================
// DATA KEPENGURUSAN
// ================================
$periode = $_GET['periode'] ?? null;

$kepengurusan = $kepengurusanModel->getAll($periode);

$daftarPeriode = $kepengurusanModel->getPeriodeList();

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
    <link rel="stylesheet" href="../asset/css/VotingKandidat.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/HomePengurus.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/Status.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Dosis:wght@200..800&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="../asset/css/font.css?v=<?php echo time(); ?>">
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
                    <li>
                        <a href="dashboard_pengurus.php?page=home_pengurus"
                            class="<?php echo ($page == 'home_pengurus') ? 'active' : ''; ?>">
                            Beranda
                        </a>
                    </li>

                    <li>
                        <a href="dashboard_pengurus.php?page=pengumuman"
                            class="<?php echo ($page == 'pengumuman') ? 'active' : ''; ?>">
                            Pengumuman
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

            <form method="POST" action="../src/controllers/AuthController.php?action=logout" class="logout-form">
                <div class="sidebar-menu-bottom">
                    <li>
                        <a href="dashboard_pengurus.php?page=profil"
                            class="<?php echo ($page == 'profil') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-user"></i>Profil
                        </a>
                    </li>
                </div>
                <button class="logout-btn">
                    <i class="fa-solid fa-right-from-bracket"></i> Keluar
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
                    include '../src/pages/Kepengurusan.php';
                    break;

                case 'keuangan':
                    include '../src/pages/keuangan.php';
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