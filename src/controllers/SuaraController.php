<?php
if(session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../models/SuaraModel.php';

$suaraModel = new SuaraModel();

if(isset($_GET['action']) && $_GET['action'] === "vote"){

    // ================= CEK LOGIN
    if(!isset($_SESSION['user'])){
        header("Location: ../../public/login.php");
        exit;
    }

    $id_pengguna = $_SESSION['user']['id_pengguna'];
    $id_calon = $_GET['id_calon'] ?? null;

    if(!$id_calon){
        $_SESSION['error'] = "Kandidat tidak valid!";
        header("Location: ../../public/dashboard_pengurus.php?page=voting");
        exit;
    }

    try {
        // ================= CEK SUDAH VOTE
        if(!$suaraModel->cekSuara($id_pengguna, $id_calon)){
            $suaraModel->createSuara([
                'id_calon'    => $id_calon,
                'id_pengguna' => $id_pengguna,
                'tanggal_dibuat' => date('Y-m-d H:i:s')
            ]);
            $_SESSION['success'] = "Vote berhasil!";
        } else {
            $_SESSION['error'] = "Kamu sudah memilih kandidat ini!";
        }
    } catch(PDOException $e){
        error_log("SuaraController Error: " . $e->getMessage());
        $_SESSION['error'] = "Terjadi kesalahan saat memberikan vote!";
    }

    // Redirect kembali ke halaman voting
    header("Location: ../../public/dashboard_pengurus.php?page=voting");
    exit;
}