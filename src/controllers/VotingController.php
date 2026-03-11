<?php
// Mulai session sekali saja
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

// Panggil model Voting
require_once __DIR__ . '/../models/VotingModel.php';
$model = new VotingModel(); // gunakan $model konsisten

/* ===============================
   CREATE VOTING
=============================== */
if(isset($_GET['action']) && $_GET['action'] == "createVoting"){

    $data = [
        'id_pengguna' => $_SESSION['user']['id_pengguna'],
        'judul' => $_POST['judul'],
        'periode' => $_POST['periode'],
        'tanggal_buka' => $_POST['tanggal_buka'],
        'tanggal_tutup' => $_POST['tanggal_tutup']
    ];

    $model->createVoting($data);
    header("Location: ../../public/dashboard_pengurus.php?page=voting");
    exit;
}

/* ===============================
   UPDATE DATA VOTING
=============================== */
if(isset($_GET['action']) && $_GET['action'] == "updateVoting"){

    $id_voting = $_POST['id_voting'];

    $data = [
        'judul' => $_POST['judul'],
        'periode' => $_POST['periode'],
        'tanggal_buka' => $_POST['tanggal_buka'],
        'tanggal_tutup' => $_POST['tanggal_tutup']
    ];

    $model->updateVoting($id_voting, $data);
    header("Location: ../../public/dashboard_pengurus.php?page=voting");
    exit;
}

/* ===============================
   CREATE KANDIDAT
=============================== */
if(isset($_GET['action']) && $_GET['action'] == "createKandidat"){

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
       $data = [
            'id_voting' => $_POST['id_voting'],
            'id_pengguna' => $_POST['id_pengguna'],
            'jabatan' => $_POST['jabatan'],
            'no_paslon' => $_POST['no_paslon'],
            'visi' => $_POST['visi'],
            'misi' => $_POST['misi']
        ];

        $model->createKandidat($data);
        header("Location: ../../public/dashboard_pengurus.php?page=voting");
        exit;
    } else {
        die("Form tidak dikirim dengan POST.");
    }
}

/* ===============================
   UPDATE KANDIDAT
=============================== */
if(isset($_GET['action']) && $_GET['action'] == "updateKandidat"){

    $data = [
        'id_calon' => $_POST['id_calon'],
        'id_voting' => $_POST['id_voting'],
        'id_pengguna' => $_POST['id_pengguna'],
        'jabatan' => $_POST['jabatan'],
        'no_paslon' => $_POST['no_paslon'],
        'visi' => $_POST['visi'],
        'misi' => $_POST['misi']
    ];

    $model->updateKandidat($data);
    header("Location: ../../public/dashboard_pengurus.php?page=voting");
    exit;
}

/* ===============================
   UPDATE STATUS
=============================== */
if(isset($_GET['action']) && $_GET['action'] == "updateStatus"){

    $id_voting = $_GET['id'];
    $status = $_GET['status'];

    $model->updateStatus($id_voting,$status);
    header("Location: ../../public/dashboard_pengurus.php?page=voting");
    exit;
}

/* ===============================
   DELETE KANDIDAT
=============================== */
if(isset($_GET['action']) && $_GET['action'] == "deleteKandidat"){

    $id = $_GET['id'];
    $model->deleteKandidat($id);
    header("Location: ../../public/dashboard_pengurus.php?page=voting");
    exit;
}

/* ===============================
   DELETE VOTING
=============================== */
if(isset($_GET['action']) && $_GET['action'] == "deleteVoting"){

    $id = $_GET['id'];
    $model->deleteVoting($id);
    header("Location: ../../public/dashboard_pengurus.php?page=voting");
    exit;
}

/* ===============================
   VOTE / TAMBAH SUARA
=============================== */
if(isset($_GET['action']) && $_GET['action'] == "vote"){

    session_start();
    $id_pengguna = $_SESSION['user']['id_pengguna'];
    $id_calon = $_GET['id_calon'];

    // Cek jika sudah memberikan suara
    if(!$model->checkSuara($id_pengguna, $id_calon)){
        $model->createSuara([
            'id_pengguna' => $id_pengguna,
            'id_calon' => $id_calon
        ]);
    }

    header("Location: ../../public/dashboard_pengurus.php?page=voting");
    exit;
}

/* ===============================
   UPDATE STATUS DAN PROSES HASIL VOTING
=============================== */
if(isset($_GET['action']) && $_GET['action'] == "updateStatus"){

    $id_voting = $_GET['id'];
    $status = $_GET['status'];

    $model->updateStatus($id_voting,$status);

    // Jika voting selesai, proses pemenang
    if($status === 'selesai'){
        $model->prosesHasilVoting($id_voting);
    }

    header("Location: ../../public/dashboard_pengurus.php?page=voting");
    exit;
}