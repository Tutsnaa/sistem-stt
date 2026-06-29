<?php
require_once __DIR__ . '/../../config/Database.php';

class AgendaModel {
    private $conn;

    public function __construct(){
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // ================= AUTO UPDATE STATUS =================
    public function autoUpdateStatus(){
    try {
        $query = "
            UPDATE agenda
            SET status = CASE
                WHEN status = 'disetujui'
                     AND NOW() >= tanggal_buka
                     AND NOW() <= tanggal_tutup
                THEN 'dibuka'

                WHEN status = 'dibuka'
                     AND NOW() > tanggal_tutup
                THEN 'selesai'

                ELSE status
            END
            WHERE status IN ('disetujui', 'dibuka')
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

    } catch(PDOException $e){
        error_log('Auto Update Status Error: ' . $e->getMessage());
    }
}

    // ================= CREATE AGENDA =================
    public function createAgenda($data){
        try {
            if(empty($data['id_pengguna'])) throw new Exception("ID Pengguna kosong");
            $query = "INSERT INTO agenda 
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
                'menunggu'
            ]);
            return $this->conn->lastInsertId();
        } catch(PDOException $e){
            error_log("Create Agenda Error: " . $e->getMessage());
            return false;
        }
    }


   public function cekJudul($judul)
{
    $stmt = $this->conn->prepare("SELECT * FROM agenda WHERE judul = ?");
    $stmt->execute([trim($judul)]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function cekPeriode($periode)
{
    $stmt = $this->conn->prepare("SELECT * FROM agenda WHERE periode = ?");
    $stmt->execute([trim($periode)]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function cekMasaAwal($masaAwal)
{
    $stmt = $this->conn->prepare("SELECT * FROM agenda WHERE masa_awal_jabatan = ?");
    $stmt->execute([$masaAwal]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function cekMasaAkhir($masaAkhir)
{
    $stmt = $this->conn->prepare("SELECT * FROM agenda WHERE masa_akhir_jabatan = ?");
    $stmt->execute([$masaAkhir]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    
    // ================= UPDATE agenda =================
    public function updateAgenda($id_agenda, $data){
        try {
            $query = "UPDATE agenda 
                      SET judul = ?, 
                          periode = ?, 
                          tanggal_buka = ?, 
                          tanggal_tutup = ?, 
                          status = ? 
                      WHERE id_agenda = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                $data['judul'],
                $data['periode'],
                $data['tanggal_buka'],
                $data['tanggal_tutup'],
                'menunggu',
                $id_agenda
            ]);
        } catch(PDOException $e){
            error_log("Update agenda Error: " . $e->getMessage());
            return false;
        }
    }

  public function cekAgendaUpdate($idAgenda, $judul, $masaAwal, $masaAkhir, $periode)
{
    $query = "SELECT *
              FROM agenda
              WHERE (
                    judul = ?
                 OR masa_awal_jabatan = ?
                 OR masa_akhir_jabatan = ?
                 OR periode = ?
              )
              AND id_agenda <> ?";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([
        trim($judul),
        $masaAwal,
        $masaAkhir,
        trim($periode),
        $idAgenda
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    // ================= DELETE agenda =================
    public function deleteAgenda($id_agenda){
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare("DELETE FROM suara_voting WHERE id_calon IN (SELECT id_calon FROM calon_kandidat WHERE id_agenda=?)");
            $stmt->execute([$id_agenda]);
            $stmt = $this->conn->prepare("DELETE FROM calon_kandidat WHERE id_agenda=?");
            $stmt->execute([$id_agenda]);
            $stmt = $this->conn->prepare("DELETE FROM agenda WHERE id_agenda=?");
            $stmt->execute([$id_agenda]);
            $this->conn->commit();
            return true;
        } catch(PDOException $e){
            $this->conn->rollBack();
            error_log("Delete Agenda Error: " . $e->getMessage());
            return false;
        }
    }

    public function getAllAgenda(){
        $stmt = $this->conn->prepare("SELECT * FROM agenda ORDER BY id_agenda DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // public function updateStatus($id_agenda, $status){
    //     $stmt = $this->conn->prepare("UPDATE agenda SET status=? WHERE id_agenda=?");
    //     return $stmt->execute([$status, $id_agenda]);
    // }

    public function updateStatus($id_agenda, $status){
    $stmt = $this->conn->prepare("
        UPDATE agenda 
        SET status = ? 
        WHERE id_agenda = ?
    ");
    return $stmt->execute([$status, $id_agenda]);
}

    // ================= AMBIL agenda BERDASARKAN CALON =================
    public function getAgendaByCalon($id_calon){
        $stmt = $this->conn->prepare("
            SELECT v.* 
            FROM agenda v
            JOIN calon_kandidat c ON c.id_agenda = v.id_agenda
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

    public function getPemenangPerJabatan($id_agenda){
    $stmt = $this->conn->prepare("
        SELECT 
            c.id_pengguna,
            c.jabatan,
            COUNT(s.id_suara) as total_suara
        FROM calon_kandidat c
        LEFT JOIN suara_voting s ON s.id_calon = c.id_calon
        WHERE c.id_agenda = ?
        GROUP BY c.jabatan, c.id_pengguna
        ORDER BY c.jabatan, total_suara DESC
    ");
    $stmt->execute([$id_agenda]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getAgendaById($id_agenda){
    $stmt = $this->conn->prepare("SELECT * FROM agenda WHERE id_agenda=?");
    $stmt->execute([$id_agenda]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function getCalonById($id_calon)
{
    $stmt = $this->conn->prepare("
        SELECT * FROM calon_kandidat
        WHERE id_calon = ?
    ");

    $stmt->execute([$id_calon]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function getAgendaByStatus($status){
    $stmt = $this->conn->prepare("SELECT * FROM agenda WHERE status = ?");
    $stmt->execute([$status]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

   public function getCalonByAgenda($id_agenda){
    $stmt = $this->conn->prepare("
        SELECT 
            c.id_calon,
            c.id_pengguna,
            c.id_agenda,
            c.jabatan,
            c.no_kandidat,
            c.visi,
            c.misi,
            c.status,
            u.nama_lengkap,
            u.foto
        FROM calon_kandidat c
        JOIN pengguna u ON u.id_pengguna = c.id_pengguna
        WHERE c.id_agenda = ?
        AND c.status != 'ditolak'
        ORDER BY c.no_kandidat ASC
    ");

    $stmt->execute([$id_agenda]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getPemenangAgenda($id_agenda)
{
    $stmt = $this->conn->prepare("
        SELECT 
            x.id_calon,
            x.jabatan,
            x.no_kandidat,
            x.visi,
            x.misi,
            x.nama_lengkap,
            x.foto,
            x.total_suara
        FROM (
            SELECT 
                c.id_calon,
                c.jabatan,
                c.no_kandidat,
                c.visi,
                c.misi,
                u.nama_lengkap,
                u.foto,
                COUNT(s.id_suara) AS total_suara,
                
                -- ranking per jabatan
                ROW_NUMBER() OVER (
                    PARTITION BY c.jabatan 
                    ORDER BY COUNT(s.id_suara) DESC
                ) AS rn

            FROM calon_kandidat c
            JOIN pengguna u 
                ON u.id_pengguna = c.id_pengguna
            LEFT JOIN suara_voting s 
                ON s.id_calon = c.id_calon
            WHERE c.id_agenda = ?
            GROUP BY c.id_calon
        ) x
        WHERE x.rn = 1
        ORDER BY x.jabatan ASC
    ");

    $stmt->execute([$id_agenda]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


}