<?php
if(session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../models/VotingModel.php';
require_once __DIR__ . '/../models/SuaraModel.php';
require_once __DIR__ . '/../models/KepengurusanModel.php';

$votingModel = new VotingModel();
$suaraModel  = new SuaraModel();
$kepengurusanModel = new KepengurusanModel();

// 🔥 AUTO UPDATE STATUS
$votingModel->autoUpdateStatus();
// 🔥 AUTO MASUK KE KEPENGURUSAN
$votingList = $votingModel->getAllVoting();

foreach($votingList as $v){

    if($v['status'] == 'selesai'){

        // cek sudah diproses atau belum
        $cek = $kepengurusanModel->cekVotingSelesai($v['id_voting']);

        if(!$cek){
            prosesPemenang($votingModel, $kepengurusanModel, $v['id_voting']);
        }
    }
}

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

        // 🔥 CEK STATUS VOTING
        $voting = $votingModel->getVotingByCalon($id_calon);

        if(!$voting || $voting['status'] !== 'dibuka'){
            $_SESSION['error'] = "Voting sudah ditutup!";
            header("Location: ../../public/dashboard_pengurus.php?page=voting");
            exit;
        }

        // 🔥 CEK SUDAH VOTE ATAU BELUM
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

// ================= CREATE VOTING =================
if($action == 'createVoting') {

    $data = [
        'id_pengguna' => $_POST['id_pengguna'],
        'judul' => $_POST['judul'],
        'periode' => $_POST['periode'],
        'tanggal_buka' => $_POST['tanggal_buka'],
        'tanggal_tutup' => $_POST['tanggal_tutup']
    ];

    $result = $votingModel->createVoting($data);

    if($result){
        $_SESSION['success'] = "Voting berhasil ditambahkan!";
    } else {
        $_SESSION['error'] = "Gagal menambahkan voting!";
    }

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

// ================= AUTO PILIH PEMENANG =================
function prosesPemenang($votingModel, $kepengurusanModel, $id_voting){

    $data = $votingModel->getPemenangPerJabatan($id_voting);

    $pemenang = [];

    foreach($data as $row){

        $jabatan = $row['jabatan'];

        // ambil yang suara terbesar saja per jabatan
        if(!isset($pemenang[$jabatan])){
            $pemenang[$jabatan] = $row;
        }
    }

    foreach($pemenang as $row){

        // INSERT ke kepengurusan
        $kepengurusanModel->insertKepengurusan([
            'id_pengguna' => $row['id_pengguna'],
            'masa_awal' => date('Y-m-d'),
            'masa_akhir' => date('Y-m-d', strtotime('+1 year')),
            'jabatan' => $row['jabatan'],
            'id_voting' => $id_voting
        ]);

        // UPDATE jabatan pengguna
        $kepengurusanModel->updateJabatanPengguna(
            $row['id_pengguna'],
            $row['jabatan']
        );
    }
}