<?php
session_start();
require_once __DIR__ . '/../models/PengumumanModel.php';

$model = new PengumumanModel();

// Tentukan halaman dashboard berdasarkan login
$userRole = $_SESSION['user']['jabatan'];

// Daftar semua jabatan yang termasuk pengurus
$pengurusRoles = ['ketua', 'wakil', 'sekretaris 1', 'sekretaris 2', 'bendahara 1', 'bendahara 2'];

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
                'status' => $_POST['status']
            ];

            $model->tambahPengumuman($data);
            header("Location: $dashboardPage");
            exit();
        }
        break;

    case 'update':
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_pengumuman'];
            $file = $_FILES['file']['name'] ?? $_POST['file_lama'];
            if(isset($_FILES['file']) && $_FILES['file']['name'] != '') {
                move_uploaded_file($_FILES['file']['tmp_name'], __DIR__ . '/../../uploads/' . $file);
            }

            $data = [
                'judul' => $_POST['judul'],
                'isi' => $_POST['isi'],
                'file' => $file,
                'status' => $_POST['status']
            ];

            $model->updatePengumuman($id, $data);
            header("Location: $dashboardPage");
            exit();
        }
        break;

    case 'hapus':
        $id = $_GET['id'];
        $model->hapusPengumuman($id);
        header("Location: $dashboardPage");
        exit();
        break;

    default:
        // Jika action tidak dikenal, redirect ke dashboard sesuai user
        header("Location: $dashboardPage");
        exit();
        break;
}