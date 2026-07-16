<?php
require_once __DIR__ . '/../../config/Database.php';

class PengumumanModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection(); // pakai koneksi dari config
    }

    // Ambil semua pengumuman
   public function getAllPengumuman($jabatan) {

    if($jabatan == 'anggota'){

        $stmt = $this->db->prepare("
            SELECT p.*, u.nama_lengkap
            FROM pengumuman p
            LEFT JOIN pengguna u ON p.id_pengguna = u.id_pengguna
            WHERE p.status = 'Disetujui'
            ORDER BY p.tanggal_dibuat DESC
        ");

        $stmt->execute();

    } else {

        $stmt = $this->db->prepare("
            SELECT p.*, u.nama_lengkap
            FROM pengumuman p
            LEFT JOIN pengguna u ON p.id_pengguna = u.id_pengguna
            ORDER BY p.tanggal_dibuat DESC
        ");

        $stmt->execute();
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    // Ambil pengumuman berdasarkan ID
    public function getPengumumanById($id) {
        $stmt = $this->db->prepare("SELECT * FROM pengumuman WHERE id_pengumuman = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tambah pengumuman
    public function tambahPengumuman($data) {
        $stmt = $this->db->prepare("INSERT INTO pengumuman (id_pengguna, judul, isi, file, status) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['id_pengguna'],
            $data['judul'],
            $data['isi'],
            $data['file'],
            $data['status']
        ]);
    }

    // Khusus cek judul dan isi
//     public function cekJudul($judul)
// {
//     $stmt = $this->db->prepare("SELECT COUNT(*) FROM pengumuman WHERE judul = ?");
//     $stmt->execute([trim($judul)]);
//     return $stmt->fetchColumn() > 0;
// }

// public function cekIsi($isi)
// {
//     $stmt = $this->db->prepare("SELECT COUNT(*) FROM pengumuman WHERE isi = ?");
//     $stmt->execute([trim($isi)]);
//     return $stmt->fetchColumn() > 0;
// }

// Cek kesamaan judul dan isi
    public function cekPengumuman($judul, $isi)
{
    $stmt = $this->db->prepare("
        SELECT COUNT(*)
        FROM pengumuman
        WHERE judul = ? AND isi = ?
    ");

    $stmt->execute([
        trim($judul),
        trim($isi)
    ]);

    return $stmt->fetchColumn() > 0;
}

    // Update pengumuman
   public function updatePengumuman($id, $data) {

    $stmt = $this->db->prepare("
        UPDATE pengumuman 
        SET judul = ?, isi = ?, file = ?, status = 'Menunggu'
        WHERE id_pengumuman = ?
    ");

    return $stmt->execute([
        $data['judul'],
        $data['isi'],
        $data['file'],
        $id
    ]);
}

// Cek kesamaan judul dan isi
public function cekPengumumanUpdate($id, $judul, $isi)
{
    $stmt = $this->db->prepare("
        SELECT COUNT(*)
        FROM pengumuman
        WHERE judul = ?
          AND isi = ?
          AND id_pengumuman != ?
    ");

    $stmt->execute([
        trim($judul),
        trim($isi),
        $id
    ]);

    return $stmt->fetchColumn() > 0;
}

    // Update status pengumuman
public function updateStatus($id, $status) {

    $stmt = $this->db->prepare("
        UPDATE pengumuman 
        SET status = ? 
        WHERE id_pengumuman = ?
    ");

    return $stmt->execute([
        $status,
        $id
    ]);
}

    // Hapus pengumuman
    public function hapusPengumuman($id) {
        $stmt = $this->db->prepare("DELETE FROM pengumuman WHERE id_pengumuman = ?");
        return $stmt->execute([$id]);
    }
}
?>