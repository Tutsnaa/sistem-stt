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

        $data = [
            'id_keuangan' => $_POST['id_keuangan'],
            'jenis' => $_POST['jenis'],
            'keterangan' => $_POST['keterangan'],
            'jumlah' => $_POST['jumlah']
        ];

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

/* =========================
   DOWNLOAD EXCEL (SUDAH FILTER 🔥)
========================= */
if (isset($_GET['action']) && $_GET['action'] == "download") {

    $filterJenis = $_GET['filter'] ?? 'all';

    // 🔥 AMBIL DATA DENGAN FILTER BULAN & TAHUN
    $data = $model->getAll();

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=data_keuangan.xls");

    echo "
    <table border='0' width='100%'>
        <tr>
            <td colspan='5' style='text-align:center; font-size:18px; font-weight:bold;'>
                DATA KEUANGAN SEKAA TRUNA
            </td>
        </tr>
        <tr>
            <td colspan='5' style='text-align:center;'>
                Periode: ".($bulan ? "Bulan $bulan" : "Semua Bulan")." - Tahun $tahun
            </td>
        </tr>
        <tr><td colspan='5'></td></tr>
    </table>
    ";

    echo "
    <table border='1' cellpadding='8' cellspacing='0' width='100%'>
        <tr style='background-color:#ff9644; color:#ffffff; text-align:center; font-weight:bold;'>
            <th>No</th>
            <th>Jenis</th>
            <th>Keterangan</th>
            <th>Jumlah</th>
            <th>Tanggal</th>
        </tr>
    ";

    $no = 1;

    foreach ($data as $row) {

        // FILTER JENIS
        if ($filterJenis == 'pemasukan' && $row['jenis'] != 'pemasukan') continue;
        if ($filterJenis == 'pengeluaran' && $row['jenis'] != 'pengeluaran') continue;

        // 🔥 FILTER BULAN & TAHUN
        $rowBulan = date('n', strtotime($row['tanggal_dibuat']));
        $rowTahun = date('Y', strtotime($row['tanggal_dibuat']));

        if ($bulan && $rowBulan != $bulan) continue;
        if ($tahun && $rowTahun != $tahun) continue;

        echo "
        <tr>
            <td>".$no++."</td>
            <td>".$row['jenis']."</td>
            <td>".$row['keterangan']."</td>
            <td>Rp ".number_format($row['jumlah'], 0, ',', '.')."</td>
            <td>".date('d-m-Y', strtotime($row['tanggal_dibuat']))."</td>
        </tr>
        ";
    }

    echo "</table>";
    exit();
}