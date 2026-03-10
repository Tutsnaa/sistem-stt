<?php
require_once __DIR__ . '/../../config/Database.php';

class PengumumanModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection(); // pakai koneksi dari config
    }

    // Ambil semua pengumuman
    public function getAllPengumuman() {
        $stmt = $this->db->query("SELECT p.*, u.nama_lengkap 
                                  FROM pengumuman p
                                  LEFT JOIN pengguna u ON p.id_pengguna = u.id_pengguna
                                  ORDER BY p.tanggal_dibuat DESC");
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

    // Update pengumuman
    public function updatePengumuman($id, $data) {
        $stmt = $this->db->prepare("UPDATE pengumuman SET judul = ?, isi = ?, file = ?, status = ? WHERE id_pengumuman = ?");
        return $stmt->execute([
            $data['judul'],
            $data['isi'],
            $data['file'],
            $data['status'],
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