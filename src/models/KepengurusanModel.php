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
    $stmt = $this->conn->prepare("
        SELECT k.*, p.nama_lengkap
        FROM kepengurusan k
        JOIN pengguna p ON k.id_pengguna = p.id_pengguna
        ORDER BY k.masa_awal_jabatan DESC
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    // CEK apakah voting sudah diproses
public function cekVotingSelesai($id_voting){
    $stmt = $this->conn->prepare("
        SELECT * FROM kepengurusan WHERE id_voting = ?
    ");
    $stmt->execute([$id_voting]);
    return $stmt->fetch();
}

// INSERT ke kepengurusan
public function insertKepengurusan($data){
    $stmt = $this->conn->prepare("
        INSERT INTO kepengurusan 
        (id_pengguna, masa_awal_jabatan, masa_akhir_jabatan, jabatan, id_voting)
        VALUES (?, ?, ?, ?, ?)
    ");
    return $stmt->execute([
        $data['id_pengguna'],
        $data['masa_awal'],
        $data['masa_akhir'],
        $data['jabatan'],
        $data['id_voting']
    ]);
}

public function hapus($id){
    $stmt = $this->conn->prepare("DELETE FROM kepengurusan WHERE id_kepengurusan = ?");
    return $stmt->execute([$id]);
}

public function updateJabatanPengguna($id_pengguna, $jabatan){
    $stmt = $this->conn->prepare("
        UPDATE pengguna 
        SET jabatan = ?
        WHERE id_pengguna = ?
    ");
    return $stmt->execute([$jabatan, $id_pengguna]);
}


}