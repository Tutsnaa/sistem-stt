<?php
require_once __DIR__ . '/../../config/Database.php';

class KepengurusanModel {
    private $conn;

    public function __construct(){
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // Tambah data ke kepengurusan
    public function tambah($data){
        $query = "INSERT INTO kepengurusan (id_pengguna, masa_awal_jabatan, masa_akhir_jabatan, jabatan) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $data['id_pengguna'],
            $data['masa_awal_jabatan'],
            $data['masa_akhir_jabatan'],
            $data['jabatan']
        ]);
    }

    // Cek apakah pengguna sudah ada di jabatan tertentu
    public function cek($id_pengguna, $jabatan){
        $query = "SELECT * FROM kepengurusan WHERE id_pengguna=? AND jabatan=?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id_pengguna, $jabatan]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    // Hapus data kepengurusan
    public function hapus($id){
        $query = "DELETE FROM kepengurusan WHERE id_kepengurusan=?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    // Ambil semua kepengurusan beserta nama pengguna
    public function getAll(){
        $query = "SELECT k.*, p.nama_lengkap 
                  FROM kepengurusan k 
                  JOIN pengguna p ON k.id_pengguna = p.id_pengguna
                  ORDER BY FIELD(jabatan, 'ketua','wakil','sekretaris 1','sekretaris 2','bendahara 1','bendahara 2')";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>