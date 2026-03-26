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
          $_SESSION['flash_message'] = "Tambah data keuangan berhasil";
        $_SESSION['flash_type'] = "info";

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
        $_SESSION['flash_type'] = "info";

        header("Location: ../../public/dashboard_pengurus.php?page=keuangan");
        exit();
    }
}

/* =========================
   HANDLE HAPUS (GET)
========================= */
if (isset($_GET['action']) && $_GET['action'] == "hapus") {

    $id = $_GET['id'];

    $model->delete($id);
      $_SESSION['flash_message'] = "Data Keuangan berhasil dihapus";
        $_SESSION['flash_type'] = "info";

    header("Location: ../../public/dashboard_pengurus.php?page=keuangan");
    exit();
}