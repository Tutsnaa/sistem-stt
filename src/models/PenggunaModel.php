<?php
require_once __DIR__ . '/../config/Database.php';

class PenggunaModel {

    private $conn;
    private $table = "pengguna";

    // ===============================
    // Constructor
    // ===============================
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // ===============================
    // Ambil semua pengguna (tanpa password)
    // ===============================
    public function getAll() {
        $query = "SELECT 
                    id_pengguna,
                    nama_lengkap,
                    foto,
                    email,
                    no_hp,
                    alamat,
                    jabatan,
                    nama_pengguna,
                    status,
                    tanggal_dibuat,
                    tanggal_diubah
                  FROM " . $this->table . "
                  ORDER BY id_pengguna DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ===============================
    // Ambil pengguna berdasarkan ID
    // ===============================
    public function getById($id) {
        $query = "SELECT 
                    id_pengguna,
                    nama_lengkap,
                    foto,
                    email,
                    no_hp,
                    alamat,
                    jabatan,
                    nama_pengguna,
                    status,
                    tanggal_dibuat,
                    tanggal_diubah
                  FROM " . $this->table . "
                  WHERE id_pengguna = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ===============================
    // Ambil berdasarkan username (untuk login)
    // ===============================
    private function getByUsername($username) {
        $query = "SELECT * FROM " . $this->table . "
                  WHERE nama_pengguna = :username
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ===============================
    // Tambah pengguna
    // ===============================
    public function create($data) {

        if (empty($data['kata_sandi'])) {
            return false;
        }

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
    // Update pengguna (tanpa password)
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
    // Update Password
    // ===============================
    public function updatePassword($id, $password) {

        $query = "UPDATE " . $this->table . "
                  SET kata_sandi = :password
                  WHERE id_pengguna = :id";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':id' => $id
        ]);
    }

    // ===============================
    // Hapus pengguna
    // ===============================
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " 
                  WHERE id_pengguna = :id";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([':id' => $id]);
    }

    // ===============================
    // Verifikasi Login (cek status aktif)
    // ===============================
    public function verifyLogin($username, $password) {

        $user = $this->getByUsername($username);

        if (
            $user &&
            $user['status'] === 'aktif' &&
            password_verify($password, $user['kata_sandi'])
        ) {
            unset($user['kata_sandi']); // hapus password sebelum return
            return $user;
        }

        return false;
    }
}