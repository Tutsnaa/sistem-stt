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

        $query = "SELECT 
                    v.id_voting,
                    v.periode,
                    v.judul,
                    v.tanggal_buka,
                    v.tanggal_tutup,
                    v.status,
                    v.tanggal_dibuat,
                    v.tanggal_diubah,
                    u.nama_lengkap
                  FROM " . $this->table . " v
                  JOIN pengguna u 
                    ON v.id_pengguna = u.id_pengguna
                  ORDER BY v.id_voting DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ===============================
    // Ambil voting aktif
    // ===============================
    public function getVotingAktif() {

        $query = "SELECT *
                  FROM " . $this->table . "
                  WHERE status = 'dibuka'
                  AND NOW() BETWEEN tanggal_buka AND tanggal_tutup
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ===============================
    // Tambah voting (default ditutup)
    // ===============================
    public function create($data) {

        $query = "INSERT INTO " . $this->table . "
                  (periode, judul, tanggal_buka, tanggal_tutup, status, id_pengguna)
                  VALUES
                  (:periode, :judul, :tanggal_buka, :tanggal_tutup, 'ditutup', :id_pengguna)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':periode' => $data['periode'],
            ':judul' => $data['judul'],
            ':tanggal_buka' => $data['tanggal_buka'],
            ':tanggal_tutup' => $data['tanggal_tutup'],
            ':id_pengguna' => $data['id_pengguna']
        ]);
    }

    // ===============================
    // Buka voting
    // ===============================
    public function bukaVoting($id) {

        $query = "UPDATE " . $this->table . "
                  SET status = 'dibuka'
                  WHERE id_voting = :id";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([':id' => $id]);
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

    // ===============================
    // Tutup otomatis jika lewat tanggal
    // ===============================
    public function autoTutup() {

        $query = "UPDATE " . $this->table . "
                  SET status = 'ditutup'
                  WHERE status = 'dibuka'
                  AND NOW() > tanggal_tutup";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute();
    }
}