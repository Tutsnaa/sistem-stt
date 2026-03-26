<?php
if(session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../models/KandidatModel.php';

$kandidatModel = new KandidatModel();

if(isset($_GET['action'])){
    $action = $_GET['action'];

    try {
        // ================= CREATE KANDIDAT
        if($action === "createKandidat" && $_SERVER['REQUEST_METHOD'] === 'POST'){
            $data = [
                'id_voting'   => $_POST['id_voting'] ?? null,
                'id_pengguna' => $_POST['id_pengguna'] ?? null,
                'jabatan'     => $_POST['jabatan'] ?? '',
                'no_paslon'   => $_POST['no_paslon'] ?? '',
                'visi'        => $_POST['visi'] ?? '',
                'misi'        => $_POST['misi'] ?? ''
            ];
            $kandidatModel->createKandidat($data);

        $_SESSION['flash_message'] = "Data kandidat berhasil ditambahkan";
        $_SESSION['flash_type'] = "success";
            // Redirect ke page voting agar kandidat muncul di tabel voting
            header("Location: ../../public/dashboard_pengurus.php?page=voting&tab=kandidat");
            exit;
        }

        // ================= UPDATE KANDIDAT
        if($action === "updateKandidat" && $_SERVER['REQUEST_METHOD'] === 'POST'){
            $data = [
                'id_calon'    => $_POST['id_calon'] ?? null,
                'id_voting'   => $_POST['id_voting'] ?? null,
                'id_pengguna' => $_POST['id_pengguna'] ?? null,
                'jabatan'     => $_POST['jabatan'] ?? '',
                'no_paslon'   => $_POST['no_paslon'] ?? '',
                'visi'        => $_POST['visi'] ?? '',
                'misi'        => $_POST['misi'] ?? ''
            ];
            $kandidatModel->updateKandidat($data);


        $_SESSION['flash_message'] = "Data kandidat berhasil diperbarui";
        $_SESSION['flash_type'] = "success";
            // Redirect ke page voting agar kandidat tetap di tabel voting
           header("Location: ../../public/dashboard_pengurus.php?page=voting&tab=kandidat");
            exit;
        }

        // ================= DELETE KANDIDAT
        if($action === "deleteKandidat"){
            $id = $_GET['id'] ?? null;
            if($id){
                $kandidatModel->deleteKandidat($id);
            }

        $_SESSION['flash_message'] = "Berhasil menghapus kandidat";
        $_SESSION['flash_type'] = "success";
            // Redirect ke page voting agar tabel kandidat tetap terlihat
            header("Location: ../../public/dashboard_pengurus.php?page=voting&tab=kandidat");
            exit;
        }

    } catch(Exception $e){
        error_log("KandidatController Error: " . $e->getMessage());
        // Redirect ke page voting dengan parameter error
        header("Location: ../../public/dashboard_pengurus.php?page=voting&error=1");
        exit;
    }
}