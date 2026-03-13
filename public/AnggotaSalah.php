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

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Anggota - STT</title>
    <link rel="stylesheet" href="../asset/css/DashboardAnggota.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/sidebar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/PagesDashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/PagesProfil.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/PagesAnggota.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/PagesKeuangan.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../asset/css/PagesPengumuman.css?v=<?php echo time(); ?>">
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

            <div class="sidebar-menu">
                <ul>
                    <li>
                        <a href="dashboard_anggota.php?page=dashboard"
                            class="<?php echo ($page == 'dashboard') ? 'active' : ''; ?>">
                            Dashboard
                        </a>
                    </li>

                    <li>
                        <a href="dashboard_anggota.php?page=pengumuman"
                            class="<?php echo ($page == 'pengumuman') ? 'active' : ''; ?>">
                            Pengumuman
                        </a>
                    </li>

                    <li>
                        <a href="dashboard_anggota.php?page=profil"
                            class="<?php echo ($page == 'profil') ? 'active' : ''; ?>">
                            Profil
                        </a>
                    </li>

                    <li>
                        <a href="dashboard_anggota.php?page=anggota"
                            class="<?php echo ($page == 'anggota') ? 'active' : ''; ?>">
                            Data Anggota
                        </a>
                    </li>

                    <li>
                        <a href="dashboard_anggota.php?page=kepengurusan"
                            class="<?php echo ($page == 'kepengurusan') ? 'active' : ''; ?>">
                            Kepengurusan
                        </a>
                    </li>

                    <li>
                        <a href="dashboard_anggota.php?page=keuangan"
                            class="<?php echo ($page == 'keuangan') ? 'active' : ''; ?>">
                            Keuangan
                        </a>
                    </li>

                    <li>
                        <a href="dashboard_anggota.php?page=voting"
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
$page = $_GET['page'] ?? 'dashboard';

switch ($page) {

    case 'profil':
        include '../src/pages/profil.php';
        break;

    case 'profil':
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
        include '../src/pages/dashboard.php';
}
?>

        </div>


    </div>

</body>

</html>














<?php if(isset($_SESSION['user']) && $_SESSION['user']['jabatan'] != 'anggota'){ ?>
<button class="btn-tambah" onclick="openTambahModal()">
    + Tambah Anggota
</button>
<?php } ?>

<h2>Daftar Anggota</h2>
<!-- =========================
         FORM PENCARIAN
    ========================== -->

<form method="GET" action="dashboard_pengurus.php" class="search-box">

    <!-- supaya tetap di halaman anggota -->
    <input type="hidden" name="page" value="anggota">

    <input type="text" name="search" placeholder="Cari nama anggota..."
        value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">

    <button type="submit">Cari</button>

    <!-- tombol kembali ke semua data -->
    <a href="dashboard_pengurus.php?page=anggota" class="btn-reset">
        Tampilkan Semua
    </a>

</form>

<?php if(isset($_SESSION['user']) && $_SESSION['user']['jabatan'] != 'anggota'){ ?>
<button class="btn-tambah" onclick="openTambahModal()">
    + Tambah Anggota
</button>
<?php } ?>

<!-- =========================
         TABEL DATA ANGGOTA
    ========================== -->

<table border="1" cellpadding="10" cellspacing="0" width="100%">

    <thead>

        <tr>
            <th>No</th>
            <th>Foto</th>
            <th>Nama Lengkap</th>
            <th>Email</th>
            <th>No HP</th>
            <th>Alamat</th>
            <th>Jabatan</th>
            <?php if(isset($_SESSION['user']) && $_SESSION['user']['jabatan'] != 'anggota'){ ?>
            <th>Username</th>
            <th>Aksi</th>
            <?php } ?>
        </tr>

    </thead>

    <tbody>

        <?php
        $no = 1;

        if(!empty($dataAnggota)){
            foreach($dataAnggota as $row){
        ?>

        <tr>

            <td><?= $no++; ?></td>

            <td style="text-align: center;">
                <?php if(!empty($row['foto'])){ ?>
                <img src="../uploads/<?= $row['foto']; ?>" width="50" height="50"
                    style="border-radius:50%; object-fit:cover;">
                <?php } else { ?>
                <img src="../asset/img/default.png" width="50" height="50">
                <?php } ?>
            </td>

            <td><?= htmlspecialchars($row['nama_lengkap']); ?></td>

            <td><?= htmlspecialchars($row['email']); ?></td>

            <td style="text-align: center;"><?= htmlspecialchars($row['no_hp']); ?></td>

            <td><?= htmlspecialchars($row['alamat']); ?></td>

            <td style="text-align: center;"><?= htmlspecialchars($row['jabatan']); ?></td>

            <?php if(isset($_SESSION['user']) && $_SESSION['user']['jabatan'] != 'anggota'){ ?>
            <td><?= htmlspecialchars($row['nama_pengguna']); ?></td>
            <?php } ?>

            <?php if(isset($_SESSION['user']) && $_SESSION['user']['jabatan'] != 'anggota'){ ?> <td>
                <div class="aksi-btn">

                    <button class="btn-edit" data-id="<?= $row['id_pengguna']; ?>"
                        data-nama="<?= $row['nama_lengkap']; ?>" data-email="<?= $row['email']; ?>"
                        data-hp="<?= $row['no_hp']; ?>" data-alamat="<?= $row['alamat']; ?>"
                        data-username="<?= $row['nama_pengguna']; ?>" onclick="openEditModal(this)">

                        Ubah

                    </button>

                    <a class="btn-hapus"
                        href="../src/controllers/PenggunaController.php?action=delete&id=<?= $row['id_pengguna']; ?>"
                        onclick="return confirm('Yakin ingin menghapus anggota ini?')">

                        Hapus

                    </a>

                </div>
            </td>
            <?php } ?>

            </td>

        </tr>

        <?php
            }
        }else{
        ?>

        <tr>
            <td colspan="6" align="center">
                Data anggota tidak ditemukan
            </td>
        </tr>

        <?php
        }
        ?>

    </tbody>

</table>








<!-- TABEL DATA ANGGOTA -->
<table class="anggota-table">
    <thead>
        <tr>
            <th>No</th>
            <th>Foto</th>
            <th>Nama Lengkap</th>
            <th>Email</th>
            <th>No HP</th>
            <th>Alamat</th>
            <th>Jabatan</th>
            <?php if(isset($_SESSION['user']) && $_SESSION['user']['jabatan'] != 'anggota'){ ?>
            <th>Username</th>
            <th>Aksi</th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php
            $no = 1;
            if(!empty($dataAnggota)){
                foreach($dataAnggota as $row){
            ?>
        <tr>
            <td><?= $no++; ?></td>
            <td style="text-align: center;">
                <?php if(!empty($row['foto'])){ ?>
                <img src="../uploads/<?= $row['foto']; ?>" width="50" height="50">
                <?php } else { ?>
                <img src="../asset/img/default.png" width="50" height="50">
                <?php } ?>
            </td>
            <td><?= htmlspecialchars($row['nama_lengkap']); ?></td>
            <td><?= htmlspecialchars($row['email']); ?></td>
            <td style="text-align: center;"><?= htmlspecialchars($row['no_hp']); ?></td>
            <td><?= htmlspecialchars($row['alamat']); ?></td>
            <td style="text-align: center;"><?= htmlspecialchars($row['jabatan']); ?></td>
            <?php if(isset($_SESSION['user']) && $_SESSION['user']['jabatan'] != 'anggota'){ ?>
            <td><?= htmlspecialchars($row['nama_pengguna']); ?></td>
            <td>
                <div class="aksi-btn">
                    <button class="btn-edit" data-id="<?= $row['id_pengguna']; ?>"
                        onclick="openEditModal(this)">Ubah</button>
                    <a class="btn-hapus"
                        href="../src/controllers/PenggunaController.php?action=delete&id=<?= $row['id_pengguna']; ?>"
                        onclick="return confirm('Yakin ingin menghapus anggota ini?')">Hapus</a>
                </div>
            </td>
            <?php } ?>
        </tr>
        <?php
                }
            }else{
            ?>
        <tr>
            <td colspan="9" align="center">Data anggota tidak ditemukan</td>
        </tr>
        <?php } ?>
    </tbody>
</table>
</div>