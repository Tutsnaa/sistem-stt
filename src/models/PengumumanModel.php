<?php
require_once __DIR__ . '/../config/Database.php';

class PengumumanModel {

    private $conn;
    private $table = "pengumuman";

    // ===============================
    // Constructor
    // ===============================
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // ===============================
    // Ambil semua pengumuman
    // ===============================
    public function getAll() {
        $query = "SELECT p.*, u.nama_lengkap 
                  FROM " . $this->table . " p
                  JOIN pengguna u ON p.id_pengguna = u.id_pengguna
                  ORDER BY id_pengumuman DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ===============================
    // Tambah pengumuman
    // ===============================
    public function create($data) {
        $query = "INSERT INTO " . $this->table . "
                  (judul, isi, file, status, id_pengguna)
                  VALUES
                  (:judul, :isi, :file, :status, :id_pengguna)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':judul' => $data['judul'],
            ':isi' => $data['isi'],
            ':file' => $data['file'],
            ':status' => $data['status'],
            ':id_pengguna' => $data['id_pengguna']
        ]);
    }

    // ===============================
    // Hapus pengumuman
    // ===============================
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id_pengumuman = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':id' => $id]);
    }
}