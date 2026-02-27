<?php
require_once __DIR__ . '/../config/Database.php';

class KeuanganModel {

    private $conn;
    private $table = "keuangan";

    // ===============================
    // Constructor (Ambil koneksi DB)
    // ===============================
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // ===============================
    // Ambil semua data keuangan
    // ===============================
    public function getAll() {

        $query = "SELECT k.*, u.nama_lengkap 
                  FROM " . $this->table . " k
                  JOIN pengguna u ON k.id_pengguna = u.id_pengguna
                  ORDER BY k.id_keuangan DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ===============================
    // Tambah data keuangan
    // ===============================
    public function create($data) {

        $query = "INSERT INTO " . $this->table . "
                  (jenis, keterangan, jumlah, file_bukti, id_pengguna)
                  VALUES
                  (:jenis, :keterangan, :jumlah, :file_bukti, :id_pengguna)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':jenis' => $data['jenis'],
            ':keterangan' => $data['keterangan'],
            ':jumlah' => $data['jumlah'],
            ':file_bukti' => $data['file_bukti'],
            ':id_pengguna' => $data['id_pengguna']
        ]);
    }

    // ===============================
    // Hapus data keuangan
    // ===============================
    public function delete($id) {

        $query = "DELETE FROM " . $this->table . " 
                  WHERE id_keuangan = :id";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}