<?php
session_start();
require_once __DIR__ . '/../models/PenggunaModel.php';
$model = new PenggunaModel();

if(isset($_GET['action']) && $_GET['action'] == "update"){

    // ambil data dari form
    $data = [
        'id_pengguna' => $_POST['id_pengguna'],
        'nama_lengkap' => $_POST['nama_lengkap'],
        'email' => $_POST['email'],
        'no_hp' => $_POST['no_hp'],
        'alamat' => $_POST['alamat'],
        'nama_pengguna' => $_POST['nama_pengguna']
    ];

    // update password jika diisi
    if(!empty($_POST['kata_sandi'])){
        $data['kata_sandi'] = password_hash($_POST['kata_sandi'], PASSWORD_DEFAULT);
    }

    // update foto jika ada file baru
    if(isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] == 0){
        $file = $_FILES['foto_profil'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif'];

        if(in_array($ext, $allowed)){
            $nama_file = time() . '_' . preg_replace('/\s+/', '_', $file['name']);
            $target_dir = __DIR__ . '/../../uploads/';
            // folder uploads sudah ada, jadi ini optional
            if(!is_dir($target_dir)){
                mkdir($target_dir, 0777, true);
            }
            $target_file = $target_dir . $nama_file;

            if(move_uploaded_file($file['tmp_name'], $target_file)){
                // simpan nama file ke key 'foto' sesuai kolom DB
                $data['foto'] = $nama_file;
            } else {
                die("Gagal mengupload foto!");
            }
        } else {
            die("Format file tidak diperbolehkan!");
        }
    }

    // panggil model update
    $update = $model->update($data);

    if($update){
        // update session agar langsung tampil di dashboard
        $_SESSION['user']['nama_lengkap'] = $data['nama_lengkap'];
        $_SESSION['user']['email'] = $data['email'];
        $_SESSION['user']['no_hp'] = $data['no_hp'];
        $_SESSION['user']['alamat'] = $data['alamat'];
        $_SESSION['user']['nama_pengguna'] = $data['nama_pengguna'];
        if(isset($data['kata_sandi'])) $_SESSION['user']['kata_sandi'] = $data['kata_sandi'];
        if(isset($data['foto'])) $_SESSION['user']['foto'] = $data['foto'];

        header("Location: ../../public/dashboard_pengurus.php?page=profil");
        exit;
    } else {
        echo "Update gagal";
    }
}