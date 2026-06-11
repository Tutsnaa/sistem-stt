<?php
session_start();
use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_SESSION['user'])) {
    header("Location: ../../public/login.php");
    exit();
}

$jabatan = strtolower($_SESSION['user']['jabatan']);

$pengurus = ['admin','ketua','wakil','sekretaris 1','sekretaris 2','bendahara 1','bendahara 2'];

if (!in_array($jabatan, $pengurus)) {
    header("Location: ../../public/dashboard_anggota.php");
    exit();
}

require_once __DIR__ . '/../models/KeuanganModel.php';
$model = new KeuanganModel();

/* ================= FILTER ================= */
$bulan         = $_GET['bulan'] ?? null;
$tahun         = $_GET['tahun'] ?? date('Y');
$tanggalAwal   = $_GET['tanggal_awal'] ?? null;
$tanggalAkhir  = $_GET['tanggal_akhir'] ?? null;
$filterJenis   = $_GET['filter'] ?? 'all';

/* ================= FUNCTION FILTER 🔥 ================= */
function filterData($row, $bulan, $tahun, $tanggalAwal, $tanggalAkhir, $filterJenis) {

    if ($row['status'] !== 'disetujui') return false;

    $tanggalRow = date('Y-m-d', strtotime($row['tanggal_dibuat']));
    $rowBulan   = date('n', strtotime($row['tanggal_dibuat']));
    $rowTahun   = date('Y', strtotime($row['tanggal_dibuat']));

    if ($tanggalAwal && $tanggalRow < $tanggalAwal) return false;
    if ($tanggalAkhir && $tanggalRow > $tanggalAkhir) return false;
    if ($bulan && $rowBulan != $bulan) return false;
    if ($tahun && $rowTahun != $tahun) return false;
    if ($filterJenis != 'all' && $row['jenis'] != $filterJenis) return false;

    return true;
}

$data = $model->getAll();

$totalPemasukan = 0;
$totalPengeluaran = 0;

foreach ($data as $row) {
    if (!filterData($row,$bulan,$tahun,$tanggalAwal,$tanggalAkhir,$filterJenis)) continue;

    if ($row['jenis'] == 'pemasukan') {
        $totalPemasukan += $row['jumlah'];
    } else {
        $totalPengeluaran += $row['jumlah'];
    }
}

$uangKas = $totalPemasukan - $totalPengeluaran;

/* ================= POST ================= */
if (isset($_POST['action'])) {

    if ($_POST['action'] == "simpan") {

        $fileName = null;

        if (!empty($_FILES['file_bukti']['name'])) {
            $fileName = time() . "_" . $_FILES['file_bukti']['name'];
            move_uploaded_file($_FILES['file_bukti']['tmp_name'],"../../uploads/".$fileName);
        }

        $model->insert([
            'id_pengguna' => $_POST['id_pengguna'],
            'jenis' => $_POST['jenis'],
            'keterangan' => $_POST['keterangan'],
            'jumlah' => $_POST['jumlah'],
            'file_bukti' => $fileName,
            'status' => 'menunggu'
        ]);

        header("Location: ../../public/dashboard_pengurus.php?page=keuangan");
        exit();
    }

    if ($_POST['action'] == "update") {

        $fileName = $_POST['file_lama'];

        if (!empty($_FILES['file_bukti']['name'])) {
            $fileName = time() . "_" . $_FILES['file_bukti']['name'];
            move_uploaded_file($_FILES['file_bukti']['tmp_name'],"../../uploads/".$fileName);
        }

        $model->update([
            'id_keuangan' => $_POST['id_keuangan'],
            'jenis' => $_POST['jenis'],
            'keterangan' => $_POST['keterangan'],
            'jumlah' => $_POST['jumlah'],
            'file_bukti' => $fileName,
            'status' => 'menunggu'
        ]);

        header("Location: ../../public/dashboard_pengurus.php?page=keuangan");
        exit();
    }
}

/* ================= DELETE ================= */
if (isset($_GET['action']) && $_GET['action'] == "hapus") {
    $model->delete($_GET['id']);
    header("Location: ../../public/dashboard_pengurus.php?page=keuangan");
    exit();
}

if(isset($_GET['action']) && $_GET['action'] == "terima"){

    $model->updateStatus($_GET['id'], 'disetujui');

    header("Location: ../../public/dashboard_pengurus.php?page=keuangan");
    exit();
}

if(isset($_GET['action']) && $_GET['action'] == "tolak"){

    $model->updateStatus($_GET['id'], 'ditolak');

    header("Location: ../../public/dashboard_pengurus.php?page=keuangan");
    exit();
}

/* ========================= DOWNLOAD EXCEL FINAL FIX 🔥 ========================= */
if (isset($_GET['action']) && $_GET['action'] == "download") {

    $filterJenis   = $_GET['filter'] ?? 'all';
    $bulan         = $_GET['bulan'] ?? null;
    $tahun         = $_GET['tahun'] ?? date('Y');
    $tanggalAwal   = $_GET['tanggal_awal'] ?? null;
    $tanggalAkhir  = $_GET['tanggal_akhir'] ?? null;

    $data = $model->getAll();

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=data_keuangan.xls");

    $jenisText = ($filterJenis == 'all') ? "Pemasukan dan Pengeluaran" : ucfirst($filterJenis);

    $periodeText = ($tanggalAwal && $tanggalAkhir)
        ? "Periode: $tanggalAwal s/d $tanggalAkhir"
        : "Semua Periode";

    echo "
    <table border='0' width='2000'>
        <tr>
            <td colspan='6' style='text-align:center; font-size:26px; font-weight:bold;'>
                SEKAA TRUNA TRUNI GALUH MANTRI
            </td>
        </tr>
        <tr>
            <td colspan='6' style='text-align:center; font-size:20px;'>
                 LAPORAN KEUANGAN
            </td>
        </tr>
        <tr>
            <td colspan='6' style='text-align:center; font-size:20px;'>
                Jenis: $jenisText
            </td>
        </tr>
        <tr>
            <td colspan='6' style='text-align:center; font-size:18px;'>
                Tanggal: $periodeText
            </td>
        </tr>
        <tr><td colspan='6'></td></tr>
    </table>
    ";

    echo "
    <table border='1' cellspacing='0' cellpadding='10' width='2000'
        style='border-collapse:collapse; font-size:16px; table-layout:fixed;'>
    ";

    if ($filterJenis == 'all') {
        echo "
        <colgroup>
            <col style='width:80px'>
            <col style='width:200px'>
            <col style='width:1200px'>
            <col style='width:260px'>
            <col style='width:260px'>
        </colgroup>";
    } else {
        echo "
        <colgroup>
            <col style='width:80px'>
            <col style='width:250px'>
            <col style='width:1400px'>
            <col style='width:270px'>
        </colgroup>";
    }

    if ($filterJenis == 'all') {
        echo "<tr style='background:#ff9644; color:#fff; font-weight:bold; text-align:center;'>
                <th>No</th><th>Tanggal</th><th>Keterangan</th>
                <th>Jumlah Masuk</th><th>Jumlah Keluar</th>
              </tr>";
    } elseif ($filterJenis == 'pemasukan') {
        echo "<tr style='background:#ff9644; color:#fff; font-weight:bold; text-align:center;'>
                <th>No</th><th>Tanggal</th><th>Keterangan</th><th>Jumlah Masuk</th>
              </tr>";
    } else {
        echo "<tr style='background:#ff9644; color:#fff; font-weight:bold; text-align:center;'>
                <th>No</th><th>Tanggal</th><th>Keterangan</th><th>Jumlah Keluar</th>
              </tr>";
    }

    $no = 1;
    $totalMasuk = 0;
    $totalKeluar = 0;
    $nowrap = "style='white-space:nowrap; overflow:hidden;'";

    foreach ($data as $row) {

        $tanggalRow = date('Y-m-d', strtotime($row['tanggal_dibuat']));
        $rowBulan = date('n', strtotime($row['tanggal_dibuat']));
        $rowTahun = date('Y', strtotime($row['tanggal_dibuat']));

        if ($tanggalAwal && $tanggalRow < $tanggalAwal) continue;
        if ($tanggalAkhir && $tanggalRow > $tanggalAkhir) continue;
        if ($bulan && $rowBulan != $bulan) continue;
        if ($tahun && $rowTahun != $tahun) continue;
        if ($filterJenis != 'all' && $row['jenis'] != $filterJenis) continue;

        if ($row['jenis'] == 'pemasukan') {
            $totalMasuk += $row['jumlah'];
        } else {
            $totalKeluar += $row['jumlah'];
        }

        $masuk  = ($row['jenis'] == 'pemasukan') ? "Rp " . number_format($row['jumlah'], 0, ',', '.') : "";
        $keluar = ($row['jenis'] == 'pengeluaran') ? "Rp " . number_format($row['jumlah'], 0, ',', '.') : "";

        echo "<tr>
                <td>$no</td>
                <td $nowrap>" . date('d-m-Y', strtotime($row['tanggal_dibuat'])) . "</td>
                <td $nowrap>{$row['keterangan']}</td>";

        if ($filterJenis == 'all') {
            echo "<td style='text-align:right;'>$masuk</td>
                  <td style='text-align:right;'>$keluar</td>";
        } elseif ($filterJenis == 'pemasukan') {
            echo "<td style='text-align:right;'>$masuk</td>";
        } else {
            echo "<td style='text-align:right;'>$keluar</td>";
        }

        echo "</tr>";
        $no++;
    }

    $saldo = $totalMasuk - $totalKeluar;

    if ($filterJenis == 'all') {
        echo "
        <tr style='font-weight:bold;'>
            <td colspan='3' align='right'>Total Pemasukan</td>
            <td align='right'>Rp " . number_format($totalMasuk, 0, ',', '.') . "</td>
            <td></td>
        </tr>
        <tr style='font-weight:bold;'>
            <td colspan='3' align='right'>Total Pengeluaran</td>
            <td></td>
            <td align='right'>Rp " . number_format($totalKeluar, 0, ',', '.') . "</td>
        </tr>
        <tr style='font-weight:bold;'>
            <td colspan='3' align='right'>Saldo Akhir</td>
            <td colspan='2' align='right'>Rp " . number_format($saldo, 0, ',', '.') . "</td>
        </tr>";
    }

    echo "</table>";
    exit();
}


/* =========================
   UNDUH LAPORAN PDF
========================= */
if (isset($_GET['action']) && $_GET['action'] == "pdf") {

    require_once __DIR__ . '/../../vendor/autoload.php';
    require_once __DIR__ . '/../models/PenggunaModel.php';

    // =========================
    // SETTING DOMPDF
    // =========================
    $options = new Options();
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);

    $modelPengguna = new PenggunaModel();

    // =========================
    // FILTER
    // =========================
    $filterJenis   = $_GET['filter'] ?? 'all';
    $bulan         = $_GET['bulan'] ?? null;
    $tahun         = $_GET['tahun'] ?? date('Y');
    $tanggalAwal   = $_GET['tanggal_awal'] ?? null;
    $tanggalAkhir  = $_GET['tanggal_akhir'] ?? null;

    $data = $model->getAll();

    // =========================
    // AMBIL TTD (ANTI ERROR 🔥)
    // =========================
    $ketua = $modelPengguna->getByJabatan('ketua') ?? [];
    $bendahara = $modelPengguna->getByJabatan('bendahara 1') ?? [];

    $namaKetua = !empty($ketua['nama_lengkap']) ? $ketua['nama_lengkap'] : '(Belum diisi)';
    $namaBendahara = !empty($bendahara['nama_lengkap']) ? $bendahara['nama_lengkap'] : '(Belum diisi)';

    // =========================
    // LOGO BASE64 (ANTI GAGAL 🔥)
    // =========================
    $logoPath = realpath(__DIR__ . '/../../asset/img/LOGOSTT.png');

    $logoBase64 = '';
    if ($logoPath && file_exists($logoPath)) {
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
    }

    // =========================
    // JUDUL
    // =========================
    $jenisText = ($filterJenis == 'all') ? "Pemasukan dan Pengeluaran" : ucfirst($filterJenis);

    $periodeText = ($tanggalAwal && $tanggalAkhir)
        ? "$tanggalAwal s/d $tanggalAkhir"
        : "Semua Periode";

    // =========================
    // HTML (TEMPLATE ASLI KAMU)
    // =========================
    $html = "
    <style>
        body { font-family: Arial; font-size: 12px; }

        .kop {
            width: 100%;
            border-bottom: 3px solid #000;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .kop-table { width: 100%; border-collapse: collapse; }
        .kop-table td { border: none !important; }

        .kop-logo { width: 100px; text-align: center; }
        .kop-logo img { width: 70px; height: 70px; }

        .kop-text { text-align: center; }
        .kop-text h2 { margin: 0; font-size: 18px; }
        .kop-text h4 { margin: 2px 0; font-weight: normal; }

        table { width: 100%; border-collapse: collapse; }
        table th { background: #eee; text-align: center; padding: 6px; border: 1px solid #000; }
        table td { border: 1px solid #000; padding: 6px; }

        .text-right { text-align: right; }
        .center { text-align: center; }

        .ttd { margin-top: 80px; width: 100%; }
        .ttd table { width: 100%; border: none; table-layout: fixed; }
        .ttd td { border: none; text-align: center; width: 50%; }

        .nama { margin-top: 60px; }
    </style>

    <!-- ================= KOP ================= -->
    <div class='kop'>
        <table class='kop-table'>
            <tr>
                <td class='kop-logo'>";

    if ($logoBase64 != '') {
        $html .= "<img src='$logoBase64'>";
    }

    $html .= "
                </td>

                <td class='kop-text'>
                    <h2>SEKAA TRUNA TRUNI GALUH MANTRI</h2>
                    <h4>LAPORAN KEUANGAN</h4>
                    <h4>Jenis: $jenisText</h4>
                    <h4>Tanggal: $periodeText</h4>
                </td>

                <td class='kop-logo'></td>
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

        $tanggalRow = date('Y-m-d', strtotime($row['tanggal_dibuat']));
        $rowBulan = date('n', strtotime($row['tanggal_dibuat']));
        $rowTahun = date('Y', strtotime($row['tanggal_dibuat']));

        if ($tanggalAwal && $tanggalRow < $tanggalAwal) continue;
        if ($tanggalAkhir && $tanggalRow > $tanggalAkhir) continue;
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
            <td class='center'>$no</td>
            <td class='center'>" . date('d-m-Y', strtotime($row['tanggal_dibuat'])) . "</td>
            <td>{$row['keterangan']}</td>";

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
            <td colspan='3' class='text-right'><b>Total Pemasukan</b></td>
            <td class='text-right'><b>Rp " . number_format($totalMasuk, 0, ',', '.') . "</b></td>
            <td></td>
        </tr>
        <tr>
            <td colspan='3' class='text-right'><b>Total Pengeluaran</b></td>
            <td></td>
            <td class='text-right'><b>Rp " . number_format($totalKeluar, 0, ',', '.') . "</b></td>
        </tr>
        <tr>
            <td colspan='3' class='text-right'><b>Saldo Akhir</b></td>
            <td colspan='2' class='text-right'><b>Rp " . number_format($saldo, 0, ',', '.') . "</b></td>
        </tr>";
    }

    $html .= "</table>";

    // ================= TTD =================
    $tanggalCetak = date('d-m-Y');

    $html .= "
    <div class='ttd'>
        <table>
            <tr>
                <td>
                    Mengetahui,<br>
                    Ketua
                    <div class='nama'>$namaKetua</div>
                </td>
                <td>
                    Denpasar, $tanggalCetak<br>
                    Bendahara
                    <div class='nama'>$namaBendahara</div>
                </td>
            </tr>
        </table>
    </div>";

    // ================= RENDER =================
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("laporan_keuangan.pdf", ["Attachment" => true]);

    exit();
}