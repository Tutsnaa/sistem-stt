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
        $query = "SELECT 
                    p.id_pengumuman,
                    p.judul,
                    p.isi,
                    p.file,
                    p.status,
                    p.tanggal_dibuat,
                    p.tanggal_diubah,
                    u.nama_lengkap
                  FROM " . $this->table . " p
                  JOIN pengguna u ON p.id_pengguna = u.id_pengguna
                  ORDER BY p.id_pengumuman DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ===============================
    // Ambil pengumuman berdasarkan ID
    // ===============================
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . "
                  WHERE id_pengumuman = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ===============================
    // Ambil pengumuman aktif (untuk publik)
    // ===============================
    public function getActive() {
        $query = "SELECT 
                    id_pengumuman,
                    judul,
                    isi,
                    file,
                    tanggal_dibuat
                  FROM " . $this->table . "
                  WHERE status = 'aktif'
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
    // Update pengumuman
    // ===============================
    public function update($id, $data) {

        $query = "UPDATE " . $this->table . " SET
                    judul = :judul,
                    isi = :isi,
                    file = :file,
                    status = :status
                  WHERE id_pengumuman = :id";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':judul' => $data['judul'],
            ':isi' => $data['isi'],
            ':file' => $data['file'],
            ':status' => $data['status'],
            ':id' => $id
        ]);
    }

    // ===============================
    // Hapus pengumuman
    // ===============================
    public function delete($id) {

        $query = "DELETE FROM " . $this->table . "
                  WHERE id_pengumuman = :id";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([':id' => $id]);
    }
}