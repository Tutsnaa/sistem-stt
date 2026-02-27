<?php
require_once __DIR__ . '/../config/Database.php';

class VotingModel {

    private $conn;
    private $table = "voting";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // ===============================
    // Ambil semua voting
    // ===============================
    public function getAll() {
        $query = "SELECT v.*, u.nama_lengkap 
                  FROM " . $this->table . " v
                  JOIN pengguna u ON v.id_pengguna = u.id_pengguna
                  ORDER BY id_voting DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ===============================
    // Tambah voting
    // ===============================
    public function create($data) {
        $query = "INSERT INTO " . $this->table . "
                  (periode, judul, tanggal_buka, tanggal_tutup, status, id_pengguna)
                  VALUES
                  (:periode, :judul, :tanggal_buka, :tanggal_tutup, :status, :id_pengguna)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':periode' => $data['periode'],
            ':judul' => $data['judul'],
            ':tanggal_buka' => $data['tanggal_buka'],
            ':tanggal_tutup' => $data['tanggal_tutup'],
            ':status' => $data['status'],
            ':id_pengguna' => $data['id_pengguna']
        ]);
    }

    // ===============================
    // Tutup voting
    // ===============================
    public function tutupVoting($id) {
        $query = "UPDATE " . $this->table . "
                  SET status = 'ditutup'
                  WHERE id_voting = :id";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':id' => $id]);
    }
}