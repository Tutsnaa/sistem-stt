<?php

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../public/login.php");
    exit();
}

$jabatan = strtolower($_SESSION['user']['jabatan']);

$pengurus = [
    'ketua',
    'wakil',
    'sekretaris 1',
    'sekretaris 2',
    'bendahara 1',
    'bendahara 2'
];

if (!in_array($jabatan, $pengurus)) {
    header("Location: ../../public/dashboard_anggota.php");
    exit();
}

require_once __DIR__ . '/../models/KeuanganModel.php';

$model = new KeuanganModel();

/* =========================
   FILTER BULAN & TAHUN 🔥
========================= */
$bulan = $_GET['bulan'] ?? null;
$tahun = $_GET['tahun'] ?? date('Y');

/* =========================
   AMBIL TOTAL (SUDAH FILTER)
========================= */
$totalPemasukan   = $model->getTotalPemasukan($bulan, $tahun);
$totalPengeluaran = $model->getTotalPengeluaran($bulan, $tahun);
$uangKas          = $totalPemasukan - $totalPengeluaran;

/* =========================
   HANDLE POST (TAMBAH & UPDATE)
========================= */
if (isset($_POST['action'])) {

    // ===== TAMBAH =====
    if ($_POST['action'] == "simpan") {

        $fileName = null;

        if (!empty($_FILES['file_bukti']['name'])) {
            $fileName = time() . "_" . $_FILES['file_bukti']['name'];

            move_uploaded_file(
                $_FILES['file_bukti']['tmp_name'],
                "../../uploads/" . $fileName
            );
        }

        $data = [
            'id_pengguna' => $_POST['id_pengguna'],
            'jenis' => $_POST['jenis'],
            'keterangan' => $_POST['keterangan'],
            'jumlah' => $_POST['jumlah'],
            'file_bukti' => $fileName
        ];

        $model->insert($data);

        $_SESSION['flash_message'] = "Data keuangan berhasil ditambahkan";
        $_SESSION['flash_type'] = "success";

        header("Location: ../../public/dashboard_pengurus.php?page=keuangan");
        exit();
    }

    // ===== UPDATE =====
   if ($_POST['action'] == "update") {

    $fileName = $_POST['file_lama']; // default pakai file lama

    // 🔥 cek apakah upload file baru
    if (!empty($_FILES['file_bukti']['name'])) {

        $fileName = time() . "_" . $_FILES['file_bukti']['name'];

        move_uploaded_file(
            $_FILES['file_bukti']['tmp_name'],
            "../../uploads/" . $fileName
        );
    }

    $data = [
        'id_keuangan' => $_POST['id_keuangan'],
        'jenis' => $_POST['jenis'],
        'keterangan' => $_POST['keterangan'],
        'jumlah' => $_POST['jumlah'],
        'file_bukti' => $fileName
    ];

    $model->update($data);

        $model->update($data);

        $_SESSION['flash_message'] = "Data Keuangan berhasil diperbarui";
        $_SESSION['flash_type'] = "success";

        header("Location: ../../public/dashboard_pengurus.php?page=keuangan");
        exit();
    }
}

/* =========================
   HANDLE HAPUS
========================= */
if (isset($_GET['action']) && $_GET['action'] == "hapus") {

    $id = $_GET['id'];

    $model->delete($id);

    $_SESSION['flash_message'] = "Data Keuangan berhasil dihapus";
    $_SESSION['flash_type'] = "success";

    header("Location: ../../public/dashboard_pengurus.php?page=keuangan");
    exit();
}

/* ========================= DOWNLOAD EXCEL FINAL FIX 🔥 ========================= */
if (isset($_GET['action']) && $_GET['action'] == "download") {

    $filterJenis = $_GET['filter'] ?? 'all';
    $data = $model->getAll();

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=data_keuangan.xls");

    // =========================
    // JUDUL
    // =========================
    $jenisText = ($filterJenis == 'all') ? "Pemasukan dan Pengeluaran" : ucfirst($filterJenis);
    $periodeText = ($bulan ? "Bulan $bulan" : "Semua Bulan") . " - Tahun $tahun";

    echo "
    <table border='0' width='2000'>
        <tr>
            <td colspan='6' style='text-align:center; font-size:26px; font-weight:bold;'>
                DATA KEUANGAN SEKAA TRUNA
            </td>
        </tr>
        <tr>
            <td colspan='6' style='text-align:center; font-size:20px;'>
                Jenis: $jenisText
            </td>
        </tr>
        <tr>
            <td colspan='6' style='text-align:center; font-size:18px;'>
                Periode: $periodeText
            </td>
        </tr>
        <tr><td colspan='6'></td></tr>
    </table>
    ";

    // =========================
    // TABEL UTAMA
    // =========================
    echo "
    <table border='1' cellspacing='0' cellpadding='10' width='2000'
        style='border-collapse:collapse; font-size:16px; table-layout:fixed;'>
    ";

    // =========================
    // COLGROUP (KETERANGAN PANJANG 🔥)
    // =========================
    if ($filterJenis == 'all') {
        echo "
        <colgroup>
            <col style='width:80px'>
            <col style='width:200px'>
            <col style='width:1200px'>
            <col style='width:260px'>
            <col style='width:260px'>
        </colgroup>
        ";
    } else {
        echo "
        <colgroup>
            <col style='width:80px'>
            <col style='width:250px'>
            <col style='width:1400px'>
            <col style='width:270px'>
        </colgroup>
        ";
    }

    // =========================
    // HEADER
    // =========================
    if ($filterJenis == 'all') {
        echo "
        <tr style='background:#ff9644; color:#fff; font-weight:bold; text-align:center; height:40px;'>
            <th>No</th>
            <th>Tanggal</th>
            <th>Keterangan</th>
            <th>Jumlah Masuk</th>
            <th>Jumlah Keluar</th>
        </tr>";
    } elseif ($filterJenis == 'pemasukan') {
        echo "
        <tr style='background:#ff9644; color:#fff; font-weight:bold; text-align:center; height:40px;'>
            <th>No</th>
            <th>Tanggal</th>
            <th>Keterangan</th>
            <th>Jumlah Masuk</th>
        </tr>";
    } else {
        echo "
        <tr style='background:#ff9644; color:#fff; font-weight:bold; text-align:center; height:40px;'>
            <th>No</th>
            <th>Tanggal</th>
            <th>Keterangan</th>
            <th>Jumlah Keluar</th>
        </tr>";
    }

    $no = 1;
    $totalMasuk = 0;
    $totalKeluar = 0;

    // STYLE TIDAK TURUN
    $nowrap = "style='white-space:nowrap; overflow:hidden;'";

    foreach ($data as $row) {

        $rowBulan = date('n', strtotime($row['tanggal_dibuat']));
        $rowTahun = date('Y', strtotime($row['tanggal_dibuat']));

        if ($bulan && $rowBulan != $bulan) continue;
        if ($tahun && $rowTahun != $tahun) continue;
        if ($filterJenis != 'all' && $row['jenis'] != $filterJenis) continue;

        if ($row['jenis'] == 'pemasukan') {
            $totalMasuk += $row['jumlah'];
        } else {
            $totalKeluar += $row['jumlah'];
        }

        if ($filterJenis == 'all') {

            $masuk = ($row['jenis'] == 'pemasukan')
                ? "Rp " . number_format($row['jumlah'], 0, ',', '.')
                : "";

            $keluar = ($row['jenis'] == 'pengeluaran')
                ? "Rp " . number_format($row['jumlah'], 0, ',', '.')
                : "";

            echo "
            <tr style='height:35px;'>
                <td>$no</td>
                <td $nowrap>" . date('d-m-Y', strtotime($row['tanggal_dibuat'])) . "</td>
                <td $nowrap>" . $row['keterangan'] . "</td>
                <td style='text-align:right;'>$masuk</td>
                <td style='text-align:right;'>$keluar</td>
            </tr>
            ";

        } elseif ($filterJenis == 'pemasukan') {

            echo "
            <tr style='height:35px;'>
                <td>$no</td>
                <td $nowrap>" . date('d-m-Y', strtotime($row['tanggal_dibuat'])) . "</td>
                <td $nowrap>" . $row['keterangan'] . "</td>
                <td style='text-align:right;'>Rp " . number_format($row['jumlah'], 0, ',', '.') . "</td>
            </tr>
            ";

        } else {

            echo "
            <tr style='height:35px;'>
                <td>$no</td>
                <td $nowrap>" . date('d-m-Y', strtotime($row['tanggal_dibuat'])) . "</td>
                <td $nowrap>" . $row['keterangan'] . "</td>
                <td style='text-align:right;'>Rp " . number_format($row['jumlah'], 0, ',', '.') . "</td>
            </tr>
            ";
        }

        $no++;
    }

    // =========================
    // TOTAL
    // =========================
    if ($filterJenis == 'all') {

        $saldo = $totalMasuk - $totalKeluar;

        echo "
        <tr style='font-weight:bold; background:#d9edf7;'>
            <td colspan='3' style='text-align:right;'>Total Pemasukan</td>
            <td style='text-align:right;'>Rp " . number_format($totalMasuk, 0, ',', '.') . "</td>
            <td></td>
        </tr>
        <tr style='font-weight:bold; background:#f2dede;'>
            <td colspan='3' style='text-align:right;'>Total Pengeluaran</td>
            <td></td>
            <td style='text-align:right;'>Rp " . number_format($totalKeluar, 0, ',', '.') . "</td>
        </tr>
        <tr style='font-weight:bold; background:#dff0d8;'>
            <td colspan='3' style='text-align:right;'>Saldo Akhir</td>
            <td colspan='2' style='text-align:right;'>Rp " . number_format($saldo, 0, ',', '.') . "</td>
        </tr>
        ";

    } elseif ($filterJenis == 'pemasukan') {

        echo "
        <tr style='font-weight:bold; background:#d9edf7;'>
            <td colspan='3' style='text-align:right;'>Total Pemasukan</td>
            <td style='text-align:right;'>Rp " . number_format($totalMasuk, 0, ',', '.') . "</td>
        </tr>
        ";

    } else {

        echo "
        <tr style='font-weight:bold; background:#f2dede;'>
            <td colspan='3' style='text-align:right;'>Total Pengeluaran</td>
            <td style='text-align:right;'>Rp " . number_format($totalKeluar, 0, ',', '.') . "</td>
        </tr>
        ";
    }

    echo "</table>";
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == "pdf") {

    require_once __DIR__ . '/../../vendor/autoload.php';

    $dompdf = new \Dompdf\Dompdf();

    $filterJenis = $_GET['filter'] ?? 'all';
    $bulan = $_GET['bulan'] ?? null;
    $tahun = $_GET['tahun'] ?? date('Y');

    $data = $model->getAll();

    // =========================
    // LOGO FIX 100% (BASE64 SAFE)
    // =========================
    $logoPath = realpath(__DIR__ . '/../../img/LogoStt.jpeg');
    $logoBase64 = '';

    if ($logoPath && file_exists($logoPath)) {
        $logoBase64 = base64_encode(file_get_contents($logoPath));
        $logoBase64 = 'data:image/jpeg;base64,' . $logoBase64;
    }

    // =========================
    // JENIS & PERIODE
    // =========================
    $jenisText = ($filterJenis == 'all') ? "Pemasukan dan Pengeluaran" : ucfirst($filterJenis);

    $bulanList = [
        1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April",
        5 => "Mei", 6 => "Juni", 7 => "Juli", 8 => "Agustus",
        9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember"
    ];

    if ($bulan) {
        $namaBulan = $bulanList[(int)$bulan];
        $periodeText = "Bulan $namaBulan Tahun $tahun";
    } else {
        $periodeText = "Semua Periode Tahun $tahun";
    }

    // =========================
    // HTML PDF
    // =========================
    $html = "
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .kop {
            width: 100%;
            border-bottom: 3px solid black;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .kop-table {
            width: 100%;
        }

        .kop-logo img {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }

        .kop-text {
            text-align: center;
        }

        .kop-text h2 {
            margin: 0;
            font-size: 18px;
        }

        .kop-text h4 {
            margin: 2px 0;
            font-weight: normal;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            border: 1px solid #000;
            padding: 6px;
        }

        table th {
            background: #eee;
        }

        .text-right {
            text-align: right;
        }

        .ttd-fixed {
            position: fixed;
            bottom: 25px;
            left: 0;
            right: 0;
        }

        .ttd-fixed table {
            width: 100%;
        }

        .ttd-fixed td {
            border: none;
            text-align: center;
        }
    </style>

    <!-- ================= KOP SURAT ================= -->
    <div class='kop'>
        <table class='kop-table'>
            <tr>
                <td class='kop-logo'>";

    // 🔥 LOGO SAFE DISPLAY
    if ($logoBase64) {
        $html .= "<img src='$logoBase64'>";
    } else {
        $html .= "<div style='width:70px;height:70px;border:1px solid #000;text-align:center;line-height:70px;'>LOGO</div>";
    }

    $html .= "</td>
                <td class='kop-text'>
                    <h2>LAPORAN KEUANGAN SEKAA TRUNA TRUNI</h2>
                    <h4>Jenis: $jenisText</h4>
                    <h4>Periode: $periodeText</h4>
                </td>
                <td style='width:80px'></td>
            </tr>
        </table>
    </div>

    <!-- ================= TABLE ================= -->
    <table>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Keterangan</th>";

    if ($filterJenis == 'all') {
        $html .= "<th>Pemasukan</th><th>Pengeluaran</th>";
    } elseif ($filterJenis == 'pemasukan') {
        $html .= "<th>Pemasukan</th>";
    } else {
        $html .= "<th>Pengeluaran</th>";
    }

    $html .= "</tr>";

    $no = 1;
    $totalMasuk = 0;
    $totalKeluar = 0;

    foreach ($data as $row) {

        $rowBulan = date('n', strtotime($row['tanggal_dibuat']));
        $rowTahun = date('Y', strtotime($row['tanggal_dibuat']));

        if ($bulan && $rowBulan != $bulan) continue;
        if ($tahun && $rowTahun != $tahun) continue;
        if ($filterJenis != 'all' && $row['jenis'] != $filterJenis) continue;

        $masuk = "";
        $keluar = "";

        if ($row['jenis'] == 'pemasukan') {
            $masuk = "Rp " . number_format($row['jumlah'], 0, ',', '.');
            $totalMasuk += $row['jumlah'];
        }

        if ($row['jenis'] == 'pengeluaran') {
            $keluar = "Rp " . number_format($row['jumlah'], 0, ',', '.');
            $totalKeluar += $row['jumlah'];
        }

        $html .= "<tr>
            <td>$no</td>
            <td>" . date('d-m-Y', strtotime($row['tanggal_dibuat'])) . "</td>
            <td>" . $row['keterangan'] . "</td>";

        if ($filterJenis == 'all') {
            $html .= "<td class='text-right'>$masuk</td>
                      <td class='text-right'>$keluar</td>";
        } elseif ($filterJenis == 'pemasukan') {
            $html .= "<td class='text-right'>$masuk</td>";
        } else {
            $html .= "<td class='text-right'>$keluar</td>";
        }

        $html .= "</tr>";
        $no++;
    }

    // ================= TOTAL =================
    if ($filterJenis == 'all') {

        $saldo = $totalMasuk - $totalKeluar;

        $html .= "
        <tr>
            <td colspan='3'><b>Total Pemasukan</b></td>
            <td class='text-right'><b>Rp " . number_format($totalMasuk, 0, ',', '.') . "</b></td>
            <td></td>
        </tr>
        <tr>
            <td colspan='3'><b>Total Pengeluaran</b></td>
            <td></td>
            <td class='text-right'><b>Rp " . number_format($totalKeluar, 0, ',', '.') . "</b></td>
        </tr>
        <tr>
            <td colspan='3'><b>Saldo Akhir</b></td>
            <td colspan='2' class='text-right'><b>Rp " . number_format($saldo, 0, ',', '.') . "</b></td>
        </tr>";
    }

    $html .= "</table>";

    // ================= TTD =================
    $tanggalCetak = date('d-m-Y');

    $html .= "
    <div class='ttd-fixed'>
        <table>
            <tr>
                <td>
                    Mengetahui,<br>
                    Ketua<br><br><br><br>
                    <b>(......................................)</b>
                </td>
                <td>
                    Denpasar, $tanggalCetak<br>
                    Bendahara<br><br><br><br>
                    <b>(......................................)</b>
                </td>
            </tr>
        </table>
    </div>
    ";

    // ================= RENDER =================
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("laporan_keuangan.pdf", ["Attachment" => true]);

    exit();
}