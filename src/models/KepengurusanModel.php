<?php
require_once __DIR__ . '/../../config/Database.php';

class KepengurusanModel {
    private $conn;

    public function __construct(){
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // Cek apakah pengguna sudah punya jabatan ini
    public function cek($id_pengguna, $jabatan){
        $stmt = $this->conn->prepare("SELECT * FROM kepengurusan WHERE id_pengguna=? AND jabatan=?");
        $stmt->execute([$id_pengguna, $jabatan]);
        return $stmt->rowCount() > 0;
    }

    public function tambah($data){
        $stmt = $this->conn->prepare("
            INSERT INTO kepengurusan (id_pengguna, masa_awal_jabatan, masa_akhir_jabatan, jabatan)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['id_pengguna'],
            $data['masa_awal_jabatan'],
            $data['masa_akhir_jabatan'],
            $data['jabatan']
        ]);
    }

    // Ambil semua data
    public function getAll(){
        $stmt = $this->conn->prepare("SELECT * FROM kepengurusan ORDER BY masa_awal_jabatan DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}