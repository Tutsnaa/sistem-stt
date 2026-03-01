<?php
require_once __DIR__ . '/../config/Database.php';

class SuaraVotingModel {

    private $conn;
    private $table = "suara_voting";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // ===============================
    // Cek apakah pengguna sudah voting
    // ===============================
    public function sudahVoting($id_pengguna) {

        $query = "SELECT id_suara 
                  FROM " . $this->table . "
                  WHERE id_pengguna = :id_pengguna
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id_pengguna' => $id_pengguna]);

        return $stmt->rowCount() > 0;
    }

    // ===============================
    // Simpan suara
    // ===============================
    public function vote($id_pengguna, $id_calon) {

        // Cegah voting dua kali
        if ($this->sudahVoting($id_pengguna)) {
            return false;
        }

        $query = "INSERT INTO " . $this->table . "
                  (id_pengguna, id_calon)
                  VALUES
                  (:id_pengguna, :id_calon)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':id_pengguna' => $id_pengguna,
            ':id_calon' => $id_calon
        ]);
    }

    // ===============================
    // Hitung total suara calon
    // ===============================
    public function countByCalon($id_calon) {

        $query = "SELECT COUNT(*) as total
                  FROM " . $this->table . "
                  WHERE id_calon = :id_calon";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id_calon' => $id_calon]);

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // ===============================
    // Ambil hasil voting (semua calon)
    // ===============================
    public function getHasilVoting() {

        $query = "SELECT 
                    c.id_calon,
                    c.nama_calon,
                    COUNT(s.id_suara) as total_suara
                  FROM calon c
                  LEFT JOIN " . $this->table . " s 
                    ON c.id_calon = s.id_calon
                  GROUP BY c.id_calon
                  ORDER BY total_suara DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}