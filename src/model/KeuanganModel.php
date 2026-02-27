<?php
require_once __DIR__ . '/../config/Database.php';

class KeuanganModel {

    private $conn;
    private $table = "keuangan";

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
                  ORDER BY id_keuangan DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ===============================
    // Tambah data keuangan
    // ===============================
    public function create($data) {
        $query = "INSERT INTO " . $this->table . "
                  (jenis, jumlah, keterangan, id_pengguna)
                  VALUES
                  (:jenis, :jumlah, :keterangan, :id_pengguna)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':jenis' => $data['jenis'],
            ':jumlah' => $data['jumlah'],
            ':keterangan' => $data['keterangan'],
            ':id_pengguna' => $data['id_pengguna']
        ]);
    }
}