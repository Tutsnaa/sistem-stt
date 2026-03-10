<?php

session_start();

if(!isset($_SESSION['user'])){
    header("Location: ../../public/login.php");
    exit;
}

if($_SESSION['user']['jabatan'] != 'pengurus'){
    header("Location: ../../public/dashboard_anggota.php");
    exit;
}

require_once __DIR__ . '/../models/KeuanganModel.php';

$model = new KeuanganModel();

if(isset($_GET['action'])){

    if($_GET['action']=="simpan"){

        $fileName = null;

        if(!empty($_FILES['file_bukti']['name'])){

            $fileName = time()."_".$_FILES['file_bukti']['name'];

            move_uploaded_file(
                $_FILES['file_bukti']['tmp_name'],
                "../../uploads/".$fileName
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

        header("Location: ../../public/dashboard_pengurus.php?page=keuangan");
    }

    if($_GET['action']=="update"){

        $data = [
            'id_keuangan' => $_POST['id_keuangan'],
            'jenis' => $_POST['jenis'],
            'keterangan' => $_POST['keterangan'],
            'jumlah' => $_POST['jumlah']
        ];

        $model->update($data);

        header("Location: ../../public/dashboard_pengurus.php?page=keuangan");
    }

    if($_GET['action']=="hapus"){

        $id = $_GET['id'];

        $model->delete($id);

        header("Location: ../../public/dashboard_pengurus.php?page=keuangan");
    }

}