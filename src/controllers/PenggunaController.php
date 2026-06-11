<?php
session_start();

/*
|--------------------------------------------------------------------------
| LOAD MODEL & DATABASE
|--------------------------------------------------------------------------
| Mengambil file model pengguna dan database
*/
require_once __DIR__ . '/../models/PenggunaModel.php';
require_once __DIR__ . '/../../config/Database.php';

$model = new PenggunaModel();

/*
|--------------------------------------------------------------------------
| CEK ACTION DARI URL
|--------------------------------------------------------------------------
| Contoh:
| ?action=create
| ?action=update
| ?action=delete
*/
$action = $_GET['action'] ?? '';

/*
|--------------------------------------------------------------------------
| 1. TAMBAH DATA ANGGOTA (CREATE)
|--------------------------------------------------------------------------
| URL: PenggunaController.php?action=create
| Method: POST
*/
if ($action == "create") {

    $data = [
        'nama_lengkap'   => $_POST['nama_lengkap'],
        'email'          => $_POST['email'],
        'no_hp'          => $_POST['no_hp'],
        'alamat'         => $_POST['alamat'],
        'nama_pengguna'  => $_POST['nama_pengguna'],
        'kata_sandi'     => $_POST['kata_sandi'],
        'jabatan' => $_POST['jabatan'] ?? 'anggota'
    ];

    /*
    |--------------------------------------------------------------------------
    | UPLOAD FOTO
    |--------------------------------------------------------------------------
    */
    if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] == 0) {

        $file = $_FILES['foto_profil'];
        $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($ext, $allowed)) {

            $nama_file  = time() . '_' . $file['name'];
            $target_dir = __DIR__ . '/../../uploads/';

            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $target_file = $target_dir . $nama_file;

            if (move_uploaded_file($file['tmp_name'], $target_file)) {
                $data['foto'] = $nama_file;
            }
        }
    }

    // cek email duplicate
if($model->emailExists($data['email'])){

    $_SESSION['flash_message'] = "Email sudah digunakan!";
    $_SESSION['flash_type'] = "danger";

   $_SESSION['old_input'] = [
    'nama_lengkap' => $_POST['nama_lengkap'],
    'email' => $_POST['email'],
    'no_hp' => $_POST['no_hp'],
    'alamat' => $_POST['alamat'],
    'nama_pengguna' => $_POST['nama_pengguna']
];

header("Location: ../../public/dashboard_pengurus.php?page=anggota&modal=tambah");
exit;
}

// cek nama pengguna duplicate
if($model->namaPenggunaExists($data['nama_pengguna'])){

    $_SESSION['flash_message'] = "Nama pengguna sudah digunakan!";
    $_SESSION['flash_type'] = "danger";

    $_SESSION['old_input'] = [
        'nama_lengkap' => $_POST['nama_lengkap'],
        'email' => $_POST['email'],
        'no_hp' => $_POST['no_hp'],
        'alamat' => $_POST['alamat'],
        'nama_pengguna' => $_POST['nama_pengguna']
    ];

    header("Location: ../../public/dashboard_pengurus.php?page=anggota&modal=tambah");
    exit;
}


    $create = $model->create($data);

    if ($create) {

     // 🔥 NOTIFIKASI
        $_SESSION['flash_message'] = "Data anggota berhasil ditambahkan";
        $_SESSION['flash_type'] = "success";

        header("Location: ../../public/dashboard_pengurus.php?page=anggota");
        exit;
    } else {
        echo "Gagal menambah anggota";
    }
}

/*
|--------------------------------------------------------------------------
| 2. UPDATE DATA ANGGOTA
|--------------------------------------------------------------------------
| URL: PenggunaController.php?action=update
| Method: POST
*/
elseif ($action == "update") {

    $data = [
    'id_pengguna'   => $_POST['id_pengguna'],
    'nama_lengkap'  => $_POST['nama_lengkap'],
    'email'         => $_POST['email'],
    'no_hp'         => $_POST['no_hp'],
    'alamat'        => $_POST['alamat'],
    'nama_pengguna' => $_POST['nama_pengguna'],

    // gunakan status lama jika status tidak dikirim
    'status' => $_POST['status'] ?? $_SESSION['user']['status']
];
    /*
    |--------------------------------------------------------------------------
    | UPDATE PASSWORD (JIKA DIISI)
    |--------------------------------------------------------------------------
    */
    if (!empty($_POST['kata_sandi'])) {
        $data['kata_sandi'] = $_POST['kata_sandi']; // kirim plaintext ke model
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE FOTO (JIKA ADA FILE BARU)
    |--------------------------------------------------------------------------
    */
    if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] == 0) {

        $file = $_FILES['foto_profil'];
        $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($ext, $allowed)) {

            $nama_file  = time() . '_' . preg_replace('/\s+/', '_', $file['name']);
            $target_dir = __DIR__ . '/../../uploads/';

            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $target_file = $target_dir . $nama_file;

            if (move_uploaded_file($file['tmp_name'], $target_file)) {
                $data['foto'] = $nama_file;
            } else {
                die("Gagal upload foto");
            }
        }
    }

// cek email duplicate selain dirinya sendiri
// CEK EMAIL DUPLIKAT (SEMUA UPDATE TERMASUK PROFIL)
if ($model->emailExists($data['email'], $data['id_pengguna'])) {

    $_SESSION['flash_message'] = "Email sudah digunakan!";
    $_SESSION['flash_type'] = "danger";
    $_SESSION['old_input_edit'] = $_POST;

    // bedakan redirect kalau dari profil
    if (isset($_POST['from']) && $_POST['from'] == "profil") {
        header("Location: ../../public/dashboard_pengurus.php?page=profil");
    } else {
        header("Location: ../../public/dashboard_pengurus.php?page=anggota&modal=edit");
    }
    exit;
}

// CEK USERNAME DUPLIKAT
if ($model->namaPenggunaExists($data['nama_pengguna'], $data['id_pengguna'])) {

    $_SESSION['flash_message'] = "Nama pengguna sudah digunakan!";
    $_SESSION['flash_type'] = "danger";
    $_SESSION['old_input_edit'] = $_POST;

    if (isset($_POST['from']) && $_POST['from'] == "profil") {
        header("Location: ../../public/dashboard_pengurus.php?page=profil");
    } else {
        header("Location: ../../public/dashboard_pengurus.php?page=anggota&modal=edit");
    }
    exit;
}

// BARU update
$update = $model->update($data);

    if ($update) {

        /*
            |--------------------------------------------------------------------------
            | UPDATE SESSION USER
            |--------------------------------------------------------------------------
            */  
            if ($_SESSION['user']['id_pengguna'] == $data['id_pengguna']) {

                $_SESSION['user']['nama_lengkap']  = $data['nama_lengkap'];
                $_SESSION['user']['email']         = $data['email'];
                $_SESSION['user']['no_hp']         = $data['no_hp'];
                $_SESSION['user']['alamat']        = $data['alamat'];
                $_SESSION['user']['nama_pengguna'] = $data['nama_pengguna'];
                $_SESSION['user']['status']        = $data['status'];


                if (isset($data['foto'])) {
                    $_SESSION['user']['foto'] = $data['foto'];
                }
            }


                /*
        |--------------------------------------------------------------------------
        | NOTIFIKASI 🔥
        |--------------------------------------------------------------------------
        */
       if ($_SESSION['user']['id_pengguna'] == $data['id_pengguna']) {
    // Update profil sendiri
    $_SESSION['flash_message'] = "Profil berhasil diperbarui";
} else {
    // Update anggota lain (oleh pengurus)
    $_SESSION['flash_message'] = "Data anggota berhasil diperbarui";
}

$_SESSION['flash_type'] = "success";


        // ================= REDIRECT SESUAI JABATAN =================
        if (isset($_POST['from']) && $_POST['from'] == "profil") {

            if ($_SESSION['user']['jabatan'] == 'anggota') {
                header("Location: ../../public/dashboard_anggota.php?page=profil");
            } else {
                header("Location: ../../public/dashboard_pengurus.php?page=profil");
            }

        } else {

            if ($_SESSION['user']['jabatan'] == 'anggota') {
                header("Location: ../../public/dashboard_anggota.php?page=anggota");
            } else {
                header("Location: ../../public/dashboard_pengurus.php?page=anggota");
            }
        }

        exit;

    } else {
        echo "Update gagal";
    }
}

/*
|--------------------------------------------------------------------------
| 3. HAPUS DATA ANGGOTA (DELETE)
|--------------------------------------------------------------------------
| URL: PenggunaController.php?action=delete&id=5
| Method: GET
*/
elseif ($action == "delete") {

    $id = $_GET['id'] ?? null;

    if (!$id) {
        $_SESSION['flash_message'] = "ID anggota tidak ditemukan";
        $_SESSION['flash_type'] = "error";
        header("Location: ../../public/dashboard_pengurus.php?page=anggota");
        exit;
    }

    $delete = $model->delete($id);

    if ($delete) {
        $_SESSION['flash_message'] = "Data anggota berhasil dihapus";
        $_SESSION['flash_type'] = "success";
    } else {
        $_SESSION['flash_message'] = "Gagal menghapus anggota. Silakan coba lagi.";
        $_SESSION['flash_type'] = "error";
    }

    // Redirect kembali ke halaman anggota
    header("Location: ../../public/dashboard_pengurus.php?page=anggota");
    exit;
}

if (isset($_GET['action']) && $_GET['action'] == "download_excel") {

    $dataAnggota = $model->getAll();

    // Header Excel
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=data_anggota.xls");

    // =========================
    // TITLE
    // =========================
    echo "<h2 style='text-align:center;'>DATA ANGGOTA SEKAA TRUNA</h2>";
    echo "<p style='text-align:center;'>Tanggal: " . date('d-m-Y H:i') . "</p><br>";

    // =========================
    // TABLE
    // =========================
    echo "<table border='1' cellpadding='5' cellspacing='0'>";

    // HEADER
    echo "<tr style='background-color:#ff9644; color:#fff; font-weight:bold; text-align:center;'>
            <th>No</th>
            <th>Nama Lengkap</th>
            <th>Email</th>
            <th>No HP</th>
            <th>Alamat</th>
            <th>Jabatan</th>
            <th>Username</th>
          </tr>";

    // DATA
    $no = 1;
    foreach ($dataAnggota as $row) {

        echo "<tr>
                <td>".$no++."</td>
                <td>".$row['nama_lengkap']."</td>
                <td>".$row['email']."</td>
                <td>".$row['no_hp']."</td>
                <td>".$row['alamat']."</td>
                <td>".$row['jabatan']."</td>
                <td>".$row['nama_pengguna']."</td>
              </tr>";
    }

    echo "</table>";
    exit();
}