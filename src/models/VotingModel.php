<?php
require_once __DIR__ . '/../../config/Database.php';

class VotingModel {
    private $conn;

    public function __construct(){
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // ================= AUTO UPDATE STATUS =================
    public function autoUpdateStatus(){
        try {
            $query = "UPDATE voting 
                      SET status = 
                        CASE 
                            WHEN NOW() < tanggal_buka THEN 'draft'
                            WHEN NOW() BETWEEN tanggal_buka AND tanggal_tutup THEN 'dibuka'
                            WHEN NOW() > tanggal_tutup THEN 'selesai'
                        END";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
        } catch(PDOException $e){
            error_log("Auto Update Status Error: " . $e->getMessage());
        }
    }

    // ================= CREATE VOTING =================
    public function createVoting($data){
        try {
            if(empty($data['id_pengguna'])) throw new Exception("ID Pengguna kosong");
            $query = "INSERT INTO voting 
(id_pengguna, judul, masa_awal_jabatan, masa_akhir_jabatan, periode, tanggal_buka, tanggal_tutup, status)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                $data['id_pengguna'],
                $data['judul'],
                $data['masa_awal_jabatan'],
                $data['masa_akhir_jabatan'],
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

    // ================= UPDATE VOTING =================
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
                $data['status'],
                $id_voting
            ]);
        } catch(PDOException $e){
            error_log("Update Voting Error: " . $e->getMessage());
            return false;
        }
    }

    // ================= DELETE VOTING =================
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

    public function updateStatus($id_voting, $status){
        $stmt = $this->conn->prepare("UPDATE voting SET status=? WHERE id_voting=?");
        return $stmt->execute([$status, $id_voting]);
    }

    // ================= AMBIL VOTING BERDASARKAN CALON =================
    public function getVotingByCalon($id_calon){
        $stmt = $this->conn->prepare("
            SELECT v.* 
            FROM voting v
            JOIN calon_kandidat c ON c.id_voting = v.id_voting
            WHERE c.id_calon = ?
        ");
        $stmt->execute([$id_calon]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ================= COUNT SUARA =================
    public function countSuara($id_calon) {
        $stmt = $this->conn->prepare("SELECT SUM(suara) as total FROM suara_voting WHERE id_calon = ?");
        $stmt->execute([$id_calon]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['total'] : 0;
    }

    public function getPemenangPerJabatan($id_voting){
    $stmt = $this->conn->prepare("
        SELECT 
            c.id_pengguna,
            c.jabatan,
            COUNT(s.id_suara) as total_suara
        FROM calon_kandidat c
        LEFT JOIN suara_voting s ON s.id_calon = c.id_calon
        WHERE c.id_voting = ?
        GROUP BY c.jabatan, c.id_pengguna
        ORDER BY c.jabatan, total_suara DESC
    ");
    $stmt->execute([$id_voting]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getVotingById($id_voting){
    $stmt = $this->conn->prepare("SELECT * FROM voting WHERE id_voting=?");
    $stmt->execute([$id_voting]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
}