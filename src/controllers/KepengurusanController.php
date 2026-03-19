<?php
session_start();
require_once __DIR__ . '/../models/KepengurusanModel.php';

$model = new KepengurusanModel();

$action = $_GET['action'] ?? null;

if($action == 'hapus'){
    $id = $_GET['id'];

    if($model->hapus($id)){
        $_SESSION['success'] = "Data berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Gagal menghapus data!";
    }

    header("Location: ../../public/dashboard_pengurus.php?page=kepengurusan");
    exit;
}