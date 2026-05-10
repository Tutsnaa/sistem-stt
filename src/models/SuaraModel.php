<?php
require_once __DIR__ . '/../../config/Database.php';

class SuaraModel {
    private $conn;

    public function __construct(){
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function createSuara($data){
        $stmt = $this->conn->prepare("
            INSERT INTO suara_voting (id_calon, id_pengguna, suara, tanggal_dibuat) 
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['id_calon'],
            $data['id_pengguna'],
            $data['suara'] ?? 1,
            $data['tanggal_dibuat'] ?? date('Y-m-d H:i:s')
        ]);
    }

//TAMBAHAN PENGECEKAN VOTING
public function cekSuaraPerJabatan($id_pengguna, $jabatan, $id_voting)
{
    $stmt = $this->conn->prepare("
        SELECT COUNT(*) as jumlah
        FROM suara_voting sv
        JOIN calon_kandidat ck ON sv.id_calon = ck.id_calon
        WHERE sv.id_pengguna = ?
        AND ck.jabatan = ?
        AND ck.id_voting = ?
    ");

    $stmt->execute([
        $id_pengguna,
        $jabatan,
        $id_voting
    ]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return ($row['jumlah'] > 0);
}

    public function cekSuara($id_pengguna, $id_calon){
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) as jumlah 
            FROM suara_voting 
            WHERE id_calon=? AND id_pengguna=?
        ");
        $stmt->execute([$id_calon, $id_pengguna]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($row['jumlah'] > 0);
    }

    public function countSuara($id_calon){
        $stmt = $this->conn->prepare("
            SELECT SUM(suara) as total 
            FROM suara_voting 
            WHERE id_calon=?
        ");
        $stmt->execute([$id_calon]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }
}