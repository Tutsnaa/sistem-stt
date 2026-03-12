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

if($action === "updateStatus"){
    $id_voting = $_GET['id'] ?? null;
    $status = $_GET['status'] ?? null;

    if($id_voting && $status){
        $votingModel->updateStatus($id_voting, $status);

        if($status === 'selesai'){
            $jabatanList = ['ketua','wakil','sekretaris 1','sekretaris 2','bendahara 1','bendahara 2'];
            foreach($jabatanList as $jabatan){
                $row = $votingModel->getPemenangByJabatan($id_voting, $jabatan);
                if($row && isset($row['id_pengguna'])){
                    if(!$kepengurusanModel->cek($row['id_pengguna'], $jabatan)){
                        $masa_awal = $row['tanggal_tutup'];
                        $masa_akhir = date('Y-m-d', strtotime('+1 year', strtotime($masa_awal)));
                        $kepengurusanModel->tambah([
                            'id_pengguna' => $row['id_pengguna'],
                            'masa_awal_jabatan' => $masa_awal,
                            'masa_akhir_jabatan' => $masa_akhir,
                            'jabatan' => $jabatan
                        ]);
                    }
                }
            }
        }
    }

    header("Location: ../../public/dashboard_pengurus.php?page=voting");
    exit;
}

if($_GET['action'] == 'updateVoting') {
    $id = $_POST['id_voting'];
    $data = [
        'judul' => $_POST['judul'],
    'periode' => $_POST['periode'],
    'tanggal_buka' => $_POST['tanggal_buka'],
    'tanggal_tutup' => $_POST['tanggal_tutup'],
    'status' => $_POST['status']
    ];
    require_once __DIR__ . '/../models/VotingModel.php';
    $model = new VotingModel();
    $model->updateVoting($id, $data);
    header("Location: ../../public/dashboard_pengurus.php?page=voting");
    exit;
}