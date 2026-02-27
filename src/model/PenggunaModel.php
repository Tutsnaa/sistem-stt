<?php
require_once __DIR__ . '/../config/Database.php';

class PenggunaModel {

    private $conn;
    private $table = "pengguna";

    // ===============================
    // Constructor (ambil koneksi DB)
    // ===============================
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // ===============================
    // Ambil semua pengguna
    // ===============================
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY id_pengguna DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ===============================
    // Ambil pengguna berdasarkan ID
    // ===============================
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id_pengguna = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ===============================
    // Ambil berdasarkan username (untuk login)
    // ===============================
    public function getByUsername($username) {
        $query = "SELECT * FROM " . $this->table . " WHERE nama_pengguna = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ===============================
    // Tambah pengguna
    // ===============================
    public function create($data) {

        $query = "INSERT INTO " . $this->table . "
            (nama_lengkap, foto, email, no_hp, alamat, jabatan, nama_pengguna, kata_sandi, status)
            VALUES
            (:nama_lengkap, :foto, :email, :no_hp, :alamat, :jabatan, :nama_pengguna, :kata_sandi, :status)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':nama_lengkap' => $data['nama_lengkap'],
            ':foto' => $data['foto'],
            ':email' => $data['email'],
            ':no_hp' => $data['no_hp'],
            ':alamat' => $data['alamat'],
            ':jabatan' => $data['jabatan'],
            ':nama_pengguna' => $data['nama_pengguna'],
            ':kata_sandi' => password_hash($data['kata_sandi'], PASSWORD_DEFAULT),
            ':status' => $data['status']
        ]);
    }

    // ===============================
    // Update pengguna
    // ===============================
    public function update($id, $data) {

        $query = "UPDATE " . $this->table . " SET
            nama_lengkap = :nama_lengkap,
            foto = :foto,
            email = :email,
            no_hp = :no_hp,
            alamat = :alamat,
            jabatan = :jabatan,
            status = :status
            WHERE id_pengguna = :id";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':nama_lengkap' => $data['nama_lengkap'],
            ':foto' => $data['foto'],
            ':email' => $data['email'],
            ':no_hp' => $data['no_hp'],
            ':alamat' => $data['alamat'],
            ':jabatan' => $data['jabatan'],
            ':status' => $data['status'],
            ':id' => $id
        ]);
    }

    // ===============================
    // Hapus pengguna
    // ===============================
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id_pengguna = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':id' => $id]);
    }

    // ===============================
    // Verifikasi Login
    // ===============================
    public function verifyLogin($username, $password) {

        $user = $this->getByUsername($username);

        if ($user && password_verify($password, $user['kata_sandi'])) {
            return $user;
        }

        return false;
    }
}