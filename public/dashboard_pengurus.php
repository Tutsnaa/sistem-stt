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
                    <li><a href="#">Data Anggota</a></li>
                    <li><a href="#">Kepengurusan</a></li>
                    <li><a href="#">Pemasukan</a></li>
                    <li><a href="#">Pengeluaran</a></li>
                    <li><a href="#">Pengumuman</a></li>
                    <li><a href="#">Voting</a></li>
                    <li><a href="#">Laporan</a></li>

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

            <div class="card-container">

                <!-- Card Pemasukan -->
                <div class="card">
                    <div class="card-icon">
                        <i class="fa-solid fa-arrow-down"></i>
                    </div>
                    <div class="card-text">
                        <h3>Pemasukan</h3>
                        <div class="card-amount">Rp 10.000</div>
                    </div>
                </div>

                <!-- Card Pengeluaran -->
                <div class="card">
                    <div class="card-icon">
                        <i class="fa-solid fa-arrow-up"></i>
                    </div>
                    <div class="card-text">
                        <h3>Pengeluaran</h3>
                        <div class="card-amount">Rp 5.000</div>
                    </div>
                </div>

                <!-- Card Uang Kas -->
                <div class="card">
                    <div class="card-icon">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                    <div class="card-text">
                        <h3>Uang Kas</h3>
                        <div class="card-amount">Rp 5.000</div>
                    </div>
                </div>

            </div>

            <div class="chart-container">

                <h3>Grafik Keuangan 5 Bulan Terakhir</h3>

                <canvas id="keuanganChart"></canvas>

            </div>

        </div>



    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    const ctx = document.getElementById('keuanganChart');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
            ],
            datasets: [{
                    label: 'Pemasukan',
                    data: [
                        500000, 700000, 650000, 800000,
                        900000, 750000, 850000, 950000,
                        700000, 880000, 920000, 1000000
                    ],
                    backgroundColor: '#4CAF50'
                },
                {
                    label: 'Pengeluaran',
                    data: [
                        300000, 450000, 400000, 500000,
                        600000, 550000, 650000, 700000,
                        480000, 520000, 600000, 750000
                    ],
                    backgroundColor: '#ff9644'
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });
    </script>
</body>

</html>