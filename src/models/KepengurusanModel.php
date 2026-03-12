<?php
require_once __DIR__ . '/../../config/Database.php';

class KepengurusanModel {
    private $conn;

    public function __construct(){
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getAll(){
        $stmt = $this->conn->prepare("
            SELECT k.*, p.nama_lengkap 
            FROM kepengurusan k 
            JOIN pengguna p ON k.id_pengguna = p.id_pengguna
            ORDER BY k.id_kepengurusan DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cek($id_pengguna, $jabatan){
        $stmt = $this->conn->prepare("SELECT COUNT(*) as jumlah FROM kepengurusan WHERE id_pengguna=? AND jabatan=?");
        $stmt->execute([$id_pengguna, $jabatan]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($row['jumlah'] > 0);
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

    public function hapus($id){
        $stmt = $this->conn->prepare("DELETE FROM kepengurusan WHERE id_kepengurusan=?");
        return $stmt->execute([$id]);
    }
}