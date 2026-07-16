<?php
session_start();
require_once __DIR__ . '/../models/PengumumanModel.php';

$model = new PengumumanModel();

// Tentukan halaman dashboard berdasarkan login
$userRole = $_SESSION['user']['jabatan'];

// Daftar semua jabatan yang termasuk pengurus
$pengurusRoles = ['admin', 'ketua', 'wakil', 'sekretaris 1', 'sekretaris 2', 'bendahara 1', 'bendahara 2'];

$dashboardPage = in_array($userRole, $pengurusRoles) 
    ? '../../public/dashboard_pengurus.php?page=pengumuman'
    : '../../public/dashboard_anggota.php?page=pengumuman';

// Ambil action dari URL
$action = $_GET['action'] ?? '';

switch($action) {
    case 'tambah':
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $file = $_FILES['file']['name'] ?? null;
            if($file) {
                move_uploaded_file($_FILES['file']['tmp_name'], __DIR__ . '/../../uploads/' . $file);
            }

            $data = [
                'id_pengguna' => $_SESSION['user']['id_pengguna'],
                'judul' => $_POST['judul'],
                'isi' => $_POST['isi'],
                'file' => $file,
                'status' =>'Menunggu'
            ];

// KHUSUS Cek judul dan isi
// if ($model->cekJudul($_POST['judul'])) {

//     $_SESSION['flash_message'] = "Judul pengumuman sudah digunakan.";
//     $_SESSION['flash_type'] = "danger";
//     $_SESSION['open_pengumuman_popup'] = true;
//     $_SESSION['old'] = $_POST;

//     header("Location: $dashboardPage");
//     exit;
// }

// if ($model->cekIsi($_POST['isi'])) {

//     $_SESSION['flash_message'] = "Isi pengumuman sudah digunakan.";
//     $_SESSION['flash_type'] = "danger";
//     $_SESSION['open_pengumuman_popup'] = true;
//     $_SESSION['old'] = $_POST;

//     header("Location: $dashboardPage");
//     exit;
// }

// Cek kesamaan judul dan isi
    if ($model->cekPengumuman($_POST['judul'], $_POST['isi'])) {

    $_SESSION['flash_message'] = "Pengumuman dengan judul dan isi yang sama sudah ada.";
    $_SESSION['flash_type'] = "danger";
    $_SESSION['open_pengumuman_popup'] = true;
    $_SESSION['old'] = $_POST;

    header("Location: $dashboardPage");
    exit;
}

            $model->tambahPengumuman($data);
            $_SESSION['flash_message'] = "Pengumuman berhasil ditambahkan";
            header("Location: $dashboardPage");
            exit();
        }
        break;

case 'update':
    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        $id = $_POST['id_pengumuman'];

        // 🔥 ambil file lama dulu
        $file = $_POST['file_lama'];

        // 🔥 cek apakah upload file baru
        if(isset($_FILES['file']) && $_FILES['file']['name'] != ''){

            $fileBaru = $_FILES['file']['name'];

            move_uploaded_file(
                $_FILES['file']['tmp_name'], 
                __DIR__ . '/../../uploads/' . $fileBaru
            );

            $file = $fileBaru; // pakai file baru
        }

        $data = [
            'judul' => $_POST['judul'],
            'isi' => $_POST['isi'],
            'file' => $file,
            'status' => 'Menunggu'
        ];

        $id = $_POST['id_pengumuman'];

        // Cek kesamaan judul dan isi
if ($model->cekPengumumanUpdate($id, $_POST['judul'], $_POST['isi'])) {

    $_SESSION['flash_message'] = "Pengumuman dengan judul dan isi yang sama sudah ada.";
    $_SESSION['flash_type'] = "danger";
    $_SESSION['open_pengumuman_popup'] = true;
    $_SESSION['old'] = $_POST;

    header("Location: $dashboardPage");
    exit;
}

        $model->updatePengumuman($id, $data);

        $_SESSION['flash_message'] = "Pengumuman berhasil diperbarui";
        $_SESSION['flash_type'] = "success";

        header("Location: $dashboardPage");
        exit();
    }
    break;

    case 'hapus':
        $id = $_GET['id'];
        $model->hapusPengumuman($id);
        $_SESSION['flash_message'] = "Pengumuman berhasil dihapus";
$_SESSION['flash_type'] = "success";
        header("Location: $dashboardPage");
        exit();
        break;

        /* ================= TOGGLE STATUS ================= */
case 'toggleStatus':

    $id = $_GET['id'];
    $status = $_GET['status'];

   $model->updateStatus($id, $status);

    $_SESSION['flash_message'] = "Status pengumuman berhasil diubah";
    $_SESSION['flash_type'] = "success";

    header("Location: $dashboardPage");
    exit();

break;

    default:
        // Jika action tidak dikenal, redirect ke dashboard sesuai user
        header("Location: $dashboardPage");
        exit();
        break;
}