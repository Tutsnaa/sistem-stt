<?php
require_once __DIR__ . '/../../config/Database.php';

class KandidatModel {
    private $conn;

    public function __construct(){
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // ====================== CREATE KANDIDAT
    public function createKandidat($data){
        try {
            $query = "INSERT INTO calon_kandidat 
                      (id_agenda, id_pengguna, jabatan, no_kandidat, visi, misi)
                      VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                $data['id_agenda'],
                $data['id_pengguna'],
                $data['jabatan'],
                $data['no_kandidat'],
                $data['visi'],
                $data['misi']
            ]);
        } catch(PDOException $e){
            error_log("Create Kandidat Error: " . $e->getMessage());
            return false;
        }
    }

    // ====================== UPDATE KANDIDAT
    public function updateKandidat($data){
        try {
            $query = "UPDATE calon_kandidat 
                      SET id_agenda=?, id_pengguna=?, jabatan=?, no_kandidat=?, visi=?, misi=? 
                      WHERE id_calon=?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                $data['id_agenda'],
                $data['id_pengguna'],
                $data['jabatan'],
                $data['no_kandidat'],
                $data['visi'],
                $data['misi'],
                $data['id_calon']
            ]);
        } catch(PDOException $e){
            error_log("Update Kandidat Error: " . $e->getMessage());
            return false;
        }
    }

    // ====================== DELETE KANDIDAT
    public function deleteKandidat($id){
        try {
            $stmt = $this->conn->prepare("DELETE FROM calon_kandidat WHERE id_calon=?");
            return $stmt->execute([$id]);
        } catch(PDOException $e){
            error_log("Delete Kandidat Error: " . $e->getMessage());
            return false;
        }
    }

    // ====================== GET ALL KANDIDAT (FIXED)
    public function getAllKandidat(){
        try {
            $query = "SELECT 
                        c.*, 
                        p.nama_lengkap,
                        p.foto, 
                        v.judul,
                        v.status
                      FROM calon_kandidat c
                      JOIN pengguna p ON c.id_pengguna = p.id_pengguna
                      JOIN agenda v ON c.id_agenda = v.id_agenda
                      ORDER BY c.id_calon ASC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $e){
            error_log("Get All Kandidat Error: " . $e->getMessage());
            return [];
        }
    }
}