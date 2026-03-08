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
        'nama_lengkap' => $_POST['nama_lengkap'],
        'email' => $_POST['email'],
        'no_hp' => $_POST['no_hp'],
        'alamat' => $_POST['alamat'],
        'jabatan' => $_POST['jabatan'],
        'nama_pengguna' => $_POST['nama_pengguna'],
        'kata_sandi' => $_POST['kata_sandi']
    ];

    /*
    |--------------------------------------------------------------------------
    | UPLOAD FOTO
    |--------------------------------------------------------------------------
    */
    if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] == 0) {

        $file = $_FILES['foto_profil'];

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        $allowed = ['jpg','jpeg','png','gif'];

        if (in_array($ext, $allowed)) {

            $nama_file = time() . '_' . $file['name'];

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

    $create = $model->create($data);

    if ($create) {
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
        'id_pengguna' => $_POST['id_pengguna'],
        'nama_lengkap' => $_POST['nama_lengkap'],
        'email' => $_POST['email'],
        'no_hp' => $_POST['no_hp'],
        'alamat' => $_POST['alamat'],
        'nama_pengguna' => $_POST['nama_pengguna']
    ];

    /*
    |--------------------------------------------------------------------------
    | UPDATE PASSWORD (JIKA DIISI)
    |--------------------------------------------------------------------------
    */
    if (!empty($_POST['kata_sandi'])) {
        $data['kata_sandi'] = password_hash($_POST['kata_sandi'], PASSWORD_DEFAULT);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE FOTO (JIKA ADA FILE BARU)
    |--------------------------------------------------------------------------
    */
    if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] == 0) {

        $file = $_FILES['foto_profil'];

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        $allowed = ['jpg','jpeg','png','gif'];

        if (in_array($ext, $allowed)) {

            $nama_file = time() . '_' . preg_replace('/\s+/', '_', $file['name']);

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

    $update = $model->update($data);

    if ($update) {

        /*
        |--------------------------------------------------------------------------
        | UPDATE SESSION USER
        |--------------------------------------------------------------------------
        */
        if($_SESSION['user']['id_pengguna'] == $data['id_pengguna']){

    $_SESSION['user']['nama_lengkap'] = $data['nama_lengkap'];
    $_SESSION['user']['email'] = $data['email'];
    $_SESSION['user']['no_hp'] = $data['no_hp'];
    $_SESSION['user']['alamat'] = $data['alamat'];
    $_SESSION['user']['nama_pengguna'] = $data['nama_pengguna'];

    if(isset($data['foto'])){
        $_SESSION['user']['foto'] = $data['foto'];
    }
}
        header("Location: ../../public/dashboard_pengurus.php?page=anggota");
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

    $id = $_GET['id'];

    $delete = $model->delete($id);

    if ($delete) {

        header("Location: ../../public/dashboard_pengurus.php?page=anggota");
        exit;

    } else {

        echo "Gagal menghapus anggota";

    }
}