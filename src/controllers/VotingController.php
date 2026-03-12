<?php
if(session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../models/VotingModel.php';
require_once __DIR__ . '/../models/SuaraModel.php';
require_once __DIR__ . '/../models/KepengurusanModel.php';

$votingModel = new VotingModel();
$suaraModel  = new SuaraModel();
$kepengurusanModel = new KepengurusanModel();

if(!isset($_SESSION['user'])){
    header("Location: ../../public/login.php");
    exit;
}

$action = $_GET['action'] ?? null;

try {
    if($action === "vote"){
        $id_calon = $_GET['id_calon'] ?? null;
        $id_pengguna = $_SESSION['user']['id_pengguna'];

        if(!$id_calon){
            $_SESSION['error'] = "Kandidat tidak valid!";
            header("Location: ../../public/dashboard_pengurus.php?page=voting");
            exit;
        }

        if(!$suaraModel->cekSuara($id_pengguna, $id_calon)){
            $suaraModel->createSuara([
                'id_calon' => $id_calon,
                'id_pengguna' => $id_pengguna,
                'tanggal_dibuat' => date('Y-m-d H:i:s')
            ]);
            $_SESSION['success'] = "Vote berhasil!";
        } else {
            $_SESSION['error'] = "Kamu sudah memilih kandidat ini!";
        }

        header("Location: ../../public/dashboard_pengurus.php?page=voting");
        exit;
    }
} catch(Exception $e){
    error_log("VotingController Error: " . $e->getMessage());
    $_SESSION['error'] = "Terjadi kesalahan!";
    header("Location: ../../public/dashboard_pengurus.php?page=voting");
    exit;
}



// ================= UPDATE VOTING =================
if($action == 'updateVoting') {
    $id = $_POST['id_voting'];
    $data = [
        'judul' => $_POST['judul'],
        'periode' => $_POST['periode'],
        'tanggal_buka' => $_POST['tanggal_buka'],
        'tanggal_tutup' => $_POST['tanggal_tutup'],
        'status' => $_POST['status']
    ];
    $votingModel->updateVoting($id, $data);
    header("Location: ../../public/dashboard_pengurus.php?page=voting");
    exit;
}