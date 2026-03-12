    <?php
    require_once __DIR__ . '/../../config/Database.php';

    class VotingModel {
        private $conn;

        public function __construct(){
            $db = new Database();
            $this->conn = $db->getConnection();
        }

        // CREATE VOTING
        public function createVoting($data){
            try {
                if(empty($data['id_pengguna'])) throw new Exception("ID Pengguna kosong");
                $query = "INSERT INTO voting (id_pengguna, judul, periode, tanggal_buka, tanggal_tutup, status)
                        VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $this->conn->prepare($query);
                $stmt->execute([
                    $data['id_pengguna'],
                    $data['judul'],
                    $data['periode'],
                    $data['tanggal_buka'],
                    $data['tanggal_tutup'],
                    'draft'
                ]);
                return $this->conn->lastInsertId();
            } catch(PDOException $e){
                error_log("Create Voting Error: " . $e->getMessage());
                return false;
            }
        }

        // UPDATE VOTING
    public function updateVoting($id_voting, $data){
        try {
            $query = "UPDATE voting 
                    SET judul = ?, 
                        periode = ?, 
                        tanggal_buka = ?, 
                        tanggal_tutup = ?, 
                        status = ? 
                    WHERE id_voting = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                $data['judul'],
                $data['periode'],
                $data['tanggal_buka'],
                $data['tanggal_tutup'],
                $data['status'],    // <- tambahkan ini
                $id_voting
            ]);
        } catch(PDOException $e){
            error_log("Update Voting Error: " . $e->getMessage());
            return false;
        }
    }

        // DELETE VOTING
        public function deleteVoting($id_voting){
            try {
                $this->conn->beginTransaction();
                $stmt = $this->conn->prepare("DELETE FROM suara_voting WHERE id_calon IN (SELECT id_calon FROM calon_kandidat WHERE id_voting=?)");
                $stmt->execute([$id_voting]);
                $stmt = $this->conn->prepare("DELETE FROM calon_kandidat WHERE id_voting=?");
                $stmt->execute([$id_voting]);
                $stmt = $this->conn->prepare("DELETE FROM voting WHERE id_voting=?");
                $stmt->execute([$id_voting]);
                $this->conn->commit();
                return true;
            } catch(PDOException $e){
                $this->conn->rollBack();
                error_log("Delete Voting Error: " . $e->getMessage());
                return false;
            }
        }

        public function getAllVoting(){
            $stmt = $this->conn->prepare("SELECT * FROM voting ORDER BY id_voting DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    // Update status voting
        public function updateStatus($id_voting, $status){
            $stmt = $this->conn->prepare("UPDATE voting SET status=? WHERE id_voting=?");
            return $stmt->execute([$status, $id_voting]);
        }

        // Ambil calon pemenang per jabatan
        public function getPemenangByJabatan($id_voting, $jabatan){
    $stmt = $this->conn->prepare("
        SELECT c.id_pengguna, COUNT(s.id_suara) AS total_suara
        FROM calon c
        LEFT JOIN suara s ON s.id_calon = c.id_calon
        WHERE c.id_voting = ? AND c.jabatan = ?
        GROUP BY c.id_pengguna
        ORDER BY total_suara DESC
        LIMIT 1
    ");
    $stmt->execute([$id_voting, $jabatan]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

        // =====================
        // Tambahkan countSuara
        // =====================
        public function countSuara($id_calon) {
        $stmt = $this->conn->prepare("SELECT SUM(suara) as total FROM suara_voting WHERE id_calon = ?");
        $stmt->execute([$id_calon]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['total'] : 0;
    }

    }