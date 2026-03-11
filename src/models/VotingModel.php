<?php
require_once __DIR__ . '/../../config/Database.php';

class VotingModel {

    private $conn;

    public function __construct(){
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    /* ===============================
       TAMBAH VOTING
    =============================== */
    public function createVoting($data){

        $query = "INSERT INTO voting
        (id_pengguna,judul,periode,tanggal_buka,tanggal_tutup,status)
        VALUES (?,?,?,?,?,?)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            $data['id_pengguna'],
            $data['judul'],
            $data['periode'],
            $data['tanggal_buka'],
            $data['tanggal_tutup'],
            'draft'
        ]);
    }

    /* ===============================
   TAMBAH KANDIDAT
=============================== */
public function createKandidat($data){
   $query = "INSERT INTO calon_kandidat
(id_voting, id_pengguna, jabatan, no_paslon, visi, misi)
VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $this->conn->prepare($query);
return $stmt->execute([
    $data['id_voting'],
    $data['id_pengguna'],
    $data['jabatan'],
    $data['no_paslon'],
    $data['visi'],
    $data['misi']
]);
}

public function updateKandidat($data)
{
    $sql = "UPDATE calon_kandidat SET
            id_voting = ?,
            id_pengguna = ?,
            jabatan = ?,
            no_paslon = ?,
            visi = ?,
            misi = ?
            WHERE id_calon = ?";  // pastikan ini sesuai PK di tabel

    $stmt = $this->conn->prepare($sql);

    return $stmt->execute([
        $data['id_voting'],
        $data['id_pengguna'],
        $data['jabatan'],
        $data['no_paslon'],
        $data['visi'],
        $data['misi'],
        $data['id_calon']
    ]);
}
    /* ===============================
       UPDATE STATUS
    =============================== */
    public function updateStatus($id_voting,$status){

        $query = "UPDATE voting SET status=? WHERE id_voting=?";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([$status,$id_voting]);
    }

    /* ===============================
       DELETE KANDIDAT
    =============================== */
    public function deleteKandidat($id){

        $query = "DELETE FROM calon_kandidat WHERE id_calon=?";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([$id]);
    }

    /* ===============================
       GET DATA VOTING
    =============================== */
    public function getAllVoting(){

        $query = "SELECT * FROM voting ORDER BY id_voting DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ===============================
       GET DATA KANDIDAT
    =============================== */
    public function getAllKandidat(){

        $query = "SELECT calon_kandidat.*, pengguna.nama_lengkap, voting.judul
        FROM calon_kandidat
        JOIN pengguna ON calon_kandidat.id_pengguna = pengguna.id_pengguna
        JOIN voting ON calon_kandidat.id_voting = voting.id_voting";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ===============================
       GET DATA ANGGOTA
    =============================== */
    public function getAllAnggota(){

        $query = "SELECT id_pengguna,nama_lengkap FROM pengguna";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ===============================
   UPDATE DATA VOTING
=============================== */
public function updateVoting($id_voting, $data){
    $query = "UPDATE voting SET judul=?, periode=?, tanggal_buka=?, tanggal_tutup=? WHERE id_voting=?";
    $stmt = $this->conn->prepare($query);
    return $stmt->execute([
        $data['judul'],
        $data['periode'],
        $data['tanggal_buka'],
        $data['tanggal_tutup'],
        $id_voting
    ]);
}
    /* ===============================
   DELETE VOTING
=============================== */
public function deleteVoting($id){

    $query = "DELETE FROM voting WHERE id_voting=?";

    $stmt = $this->conn->prepare($query);

    return $stmt->execute([$id]);
}


/* ======================
   CATAT SUARA
====================== */
public function createSuara($data){
    // Masukkan suara baru
    $query = "INSERT INTO suara_voting (id_pengguna, id_calon) VALUES (?, ?)";
    $stmt = $this->conn->prepare($query);
    $stmt->execute([
        $data['id_pengguna'],
        $data['id_calon']
    ]);

    // Update total suara untuk kandidat ini
    $this->updateTotalSuara($data['id_calon']);

    return true;
}

/* ======================
   UPDATE TOTAL SUARA
====================== */
public function updateTotalSuara($id_calon){
    // Hitung total suara dari tabel suara_voting
    $queryCount = "SELECT COUNT(*) as total FROM suara_voting WHERE id_calon = ?";
    $stmt = $this->conn->prepare($queryCount);
    $stmt->execute([$id_calon]);
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Update kolom total_suara
    $queryUpdate = "UPDATE suara_voting SET total_suara = ? WHERE id_calon = ?";
    $stmtUpdate = $this->conn->prepare($queryUpdate);
    return $stmtUpdate->execute([$total, $id_calon]);
}

/* ======================
   CEK SUARA SUDAH ADA
====================== */
public function checkSuara($id_pengguna, $id_calon){
    $query = "SELECT * FROM suara_voting WHERE id_pengguna=? AND id_calon=?";
    $stmt = $this->conn->prepare($query);
    $stmt->execute([$id_pengguna, $id_calon]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/* ======================
   HITUNG TOTAL SUARA PER KANDIDAT
====================== */
public function countSuara($id_calon){
    $query = "SELECT total_suara FROM suara_voting WHERE id_calon=?";
    $stmt = $this->conn->prepare($query);
    $stmt->execute([$id_calon]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['total_suara'] : 0;
}


/* ===============================
   PROSES HASIL VOTING
   Tambahkan pemenang ke kepengurusan & update pengguna
=============================== */
public function prosesHasilVoting($id_voting){

    // Ambil kandidat pemenang tiap jabatan
    $query = "SELECT c.id_pengguna, c.jabatan, v.tanggal_tutup
              FROM calon_kandidat c
              JOIN voting v ON c.id_voting = v.id_voting
              LEFT JOIN kepengurusan k ON k.id_pengguna = c.id_pengguna AND k.jabatan = c.jabatan
              WHERE c.id_voting = ? 
              ORDER BY (SELECT COUNT(*) FROM suara_voting s WHERE s.id_calon = c.id_calon) DESC";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([$id_voting]);
    $pemenang = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach($pemenang as $row){

        // Cek jika belum ada di kepengurusan
        $cek = $this->conn->prepare("SELECT * FROM kepengurusan WHERE id_pengguna=? AND jabatan=?");
        $cek->execute([$row['id_pengguna'],$row['jabatan']]);
        if($cek->rowCount() > 0) continue; // sudah ada, skip

        // Tambah ke kepengurusan
        $insert = $this->conn->prepare("
            INSERT INTO kepengurusan 
            (id_pengguna, masa_awal_jabatan, masa_akhir_jabatan, jabatan, tanggal_dibuat, tanggal_diubah)
            VALUES (?, ?, ?, ?, NOW(), NOW())
        ");
        $masa_awal = $row['tanggal_tutup'];
        $masa_akhir = date('Y-m-d', strtotime('+1 year', strtotime($masa_awal))); // 1 tahun masa jabatan
        $insert->execute([$row['id_pengguna'],$masa_awal,$masa_akhir,$row['jabatan']]);

        // Update jabatan di tabel pengguna
        $updatePengguna = $this->conn->prepare("UPDATE pengguna SET jabatan=? WHERE id_pengguna=?");
        $updatePengguna->execute([$row['jabatan'],$row['id_pengguna']]);
    }

    return true;
}
}