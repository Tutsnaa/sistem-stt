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
    // Simpan suara
    // ===============================
    public function vote($id_pengguna, $id_calon) {

        $query = "INSERT INTO " . $this->table . "
                  (id_user, id_calon)
                  VALUES
                  (:id_pengguna, :id_calon)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':id_pengguna' => $id_pengguna,
            ':id_calon' => $id_calon
        ]);
    }
}