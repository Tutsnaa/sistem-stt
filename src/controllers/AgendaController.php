<?php
if(session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../models/AgendaModel.php';
require_once __DIR__ . '/../models/SuaraModel.php';
require_once __DIR__ . '/../models/KepengurusanModel.php';

$agendaModel = new AgendaModel();
$suaraModel  = new SuaraModel();
$kepengurusanModel = new KepengurusanModel();

$action = $_GET['action'] ?? null;
$page   = $_GET['page'] ?? null;
function autoProcessAgenda($agendaModel, $kepengurusanModel){

    $agendaModel->autoUpdateStatus();

    $agendaList = $agendaModel->getAllagenda();

    foreach($agendaList as $v){

        if($v['status'] == 'selesai'){

            $cek = $kepengurusanModel->cekagendaSelesai($v['id_agenda']);

            if(!$cek){

                $dataPemenang = $agendaModel->getPemenangPerJabatan($v['id_agenda']);

                if(!empty($dataPemenang)){
                    prosesPemenang($agendaModel, $kepengurusanModel, $v['id_agenda']);
                }
            }
        }
    }
}


if(!isset($_SESSION['user'])){
    header("Location: ../../public/login.php");
    exit;
}

$page = $_GET['page'] ?? null;

if($page === 'voting'){
    autoProcessAgenda($agendaModel, $kepengurusanModel);
}

try {
    if($action === "vote"){
        $id_calon = $_GET['id_calon'] ?? null;
        $id_pengguna = $_SESSION['user']['id_pengguna'];
        $jabatan = $_SESSION['user']['jabatan'];

        $dashboard = ($jabatan === 'anggota') 
            ? '../../public/dashboard_anggota.php?page=voting_anggota' 
            : '../../public/dashboard_pengurus.php?page=voting&tab=voting';

        if(!$id_calon){
            $_SESSION['error'] = "Kandidat tidak valid!";
            header("Location: $dashboard");
            exit;
        }

        $agenda = $agendaModel->getagendaByCalon($id_calon);

        if(!$agenda || $agenda['status'] !== 'dibuka'){
            $_SESSION['error'] = "agenda sudah ditutup!";
            header("Location: $dashboard");
            exit;
        }

        $calon = $agendaModel->getCalonById($id_calon);

if(!$calon){
    $_SESSION['flash_message'] = "Data calon tidak ditemukan!";
    $_SESSION['flash_type'] = "danger";
    header("Location: $dashboard");
    exit;
}

if(!$suaraModel->cekSuaraPerJabatan(
    $id_pengguna,
    $calon['jabatan'],
    $calon['id_agenda']
)){
    
    $suaraModel->createSuara([
        'id_calon' => $id_calon,
        'id_pengguna' => $id_pengguna,
        'tanggal_dibuat' => date('Y-m-d H:i:s')
    ]);

    $_SESSION['flash_message'] = "Vote berhasil!";
    $_SESSION['flash_type'] = "success";

} else {

    $_SESSION['flash_message'] = "Kamu sudah memilih pada jabatan ini!";
    $_SESSION['flash_type'] = "danger";
}
        header("Location: $dashboard");
        exit;
    }
} catch(Exception $e){
    $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
    header("Location: $dashboard");
    exit;
}
// ================= CREATE agenda =================
if($action == 'createAgenda') {

    $data = [
    'id_pengguna' => $_POST['id_pengguna'],
    'judul' => $_POST['judul'],
    'masa_awal_jabatan' => $_POST['masa_awal_jabatan'],
    'masa_akhir_jabatan' => $_POST['masa_akhir_jabatan'],
    'periode' => $_POST['periode'],
    'status' => 'menunggu',
    'tanggal_buka' => $_POST['tanggal_buka'],
    'tanggal_tutup' => $_POST['tanggal_tutup']
    
];

    $result = $agendaModel->createAgenda($data);
     $_SESSION['flash_message'] = " agenda berhasil ditambahkan";
        $_SESSION['flash_type'] = "success";

    // if($result){
    //     $_SESSION['success'] = "agenda berhasil ditambahkan!";
    // } else {
    //     $_SESSION['error'] = "Gagal menambahkan agenda!";
    // }

    header("Location: ../../public/dashboard_pengurus.php?page=voting&tab=agenda");
    exit;
}

// ================= UPDATE agenda =================
if($action == 'updateAgenda') {
    $id = $_POST['id_agenda'];
    $data = [
        'judul' => $_POST['judul'],
        'periode' => $_POST['periode'],
        'tanggal_buka' => $_POST['tanggal_buka'],
        'tanggal_tutup' => $_POST['tanggal_tutup'],
        'status' => 'menunggu'
    ];
    $agendaModel->updateAgenda($id, $data);
    $_SESSION['flash_message'] = "agenda berhasil diperbarui";
    $_SESSION['flash_type'] = "success";
    header("Location: ../../public/dashboard_pengurus.php?page=voting&tab=agenda");
    exit;
}

// ================= AUTO PILIH PEMENANG =================
function prosesPemenang($agendaModel, $kepengurusanModel, $id_agenda){

    $data = $agendaModel->getPemenangPerJabatan($id_agenda);
    $agenda = $agendaModel->getAgendaById($id_agenda);

    // if(empty($data)){
    //     die("DATA PEMENANG KOSONG");
    // }
    if(empty($data)){
    // Tidak ada pemenang, skip saja
    return false;
}

    $pemenang = [];

    foreach($data as $row){
        if(!isset($pemenang[$row['jabatan']])){
            $pemenang[$row['jabatan']] = $row;
        }
    }

    foreach($pemenang as $row){

        // 🔥 turunkan pengurus lama
        $kepengurusanModel->turunkanPengurusLama(
            $row['jabatan'],
            $row['id_pengguna']
        );

        // 🔥 insert
        $result = $kepengurusanModel->insertKepengurusan([
            'id_pengguna' => $row['id_pengguna'],
            'masa_awal' => $agenda['masa_awal_jabatan'],
            'masa_akhir' => $agenda['masa_akhir_jabatan'],
            'jabatan' => $row['jabatan'],
            'id_agenda' => $id_agenda
        ]);

        if(!$result){
            die("GAGAL INSERT KE KEPENGURUSAN");
        }

        // 🔥 update jabatan
        $kepengurusanModel->updateJabatanPengguna(
            $row['id_pengguna'],
            $row['jabatan']
        );
    }

    
}

if($action === "terima"){

    $id = $_GET['id'] ?? null;

    if(!$id){
        $_SESSION['flash_message'] = "ID tidak valid!";
        $_SESSION['flash_type'] = "danger";
        header("Location: ../../public/dashboard_pengurus.php?page=voting&tab=agenda");
        exit;
    }

    $agendaModel->updateStatus($id, 'disetujui');

    $_SESSION['flash_message'] = "Agenda disetujui!";
    $_SESSION['flash_type'] = "success";

    header("Location: ../../public/dashboard_pengurus.php?page=voting&tab=agenda");
    exit;
}

if($action === "tolak"){

    $id = $_GET['id'] ?? null;

    if(!$id){
        $_SESSION['flash_message'] = "ID tidak valid!";
        $_SESSION['flash_type'] = "danger";
        header("Location: ../../public/dashboard_pengurus.php?page=voting&tab=agenda");
        exit;
    }

    $agendaModel->updateStatus($id, 'ditolak');

    $_SESSION['flash_message'] = "Agenda ditolak!";
    $_SESSION['flash_type'] = "danger";

    header("Location: ../../public/dashboard_pengurus.php?page=voting&tab=agenda");
    exit;
}

// ================= HAPUS agenda =================
if($action == 'hapus') {

    $id = $_GET['id'] ?? null;

    if(!$id){
        $_SESSION['flash_message'] = "ID tidak valid!";
        $_SESSION['flash_type'] = "danger";
        header("Location: ../../public/dashboard_pengurus.php?page=voting&tab=agenda");
    }

    // eksekusi hapus
    $result = $agendaModel->deleteAgenda($id);

    if($result){
        $_SESSION['flash_message'] = "agenda berhasil dihapus!";
        $_SESSION['flash_type'] = "success";
    } else {
        $_SESSION['flash_message'] = "Gagal menghapus agenda!";
        $_SESSION['flash_type'] = "danger";
    }

    header("Location: ../../public/dashboard_pengurus.php?page=voting&tab=agenda");
    exit;
}