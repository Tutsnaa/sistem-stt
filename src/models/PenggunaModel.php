<?php
require_once __DIR__ . '/../../config/Database.php';

class PenggunaModel {

    private $conn;
    private $table = "pengguna";

    private $allowedJabatan = [
        'ketua','wakil','sekretaris 1','sekretaris 2',
        'bendahara 1','bendahara 2','anggota'
    ];

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
                  FROM {$this->table}
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
                  FROM {$this->table}
                  WHERE id_pengguna = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ===============================
    // Cek Username
    // ===============================
    public function usernameExists($nama_pengguna) {

        $query = "SELECT id_pengguna FROM {$this->table}
                  WHERE nama_pengguna = :nama_pengguna
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':nama_pengguna' => $nama_pengguna]);

        return $stmt->rowCount() > 0;
    }

    // ===============================
    // Cek Email
    // ===============================
    public function emailExists($email) {

        $query = "SELECT id_pengguna FROM {$this->table}
                  WHERE email = :email
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':email' => $email]);

        return $stmt->rowCount() > 0;
    }

    // ===============================
    // Tambah pengguna
    // ===============================
    public function create($data) {

        if (
            empty($data['kata_sandi']) ||
            !in_array($data['jabatan'], $this->allowedJabatan)
        ) {
            return false;
        }

        if ($this->usernameExists($data['nama_pengguna']) ||
            $this->emailExists($data['email'])) {
            return false;
        }

        $query = "INSERT INTO {$this->table}
            (nama_lengkap, foto, email, no_hp, alamat, jabatan, nama_pengguna, kata_sandi, status)
            VALUES
            (:nama_lengkap, :foto, :email, :no_hp, :alamat, :jabatan, :nama_pengguna, :kata_sandi, :status)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':nama_lengkap'  => $data['nama_lengkap'],
            ':foto'          => $data['foto'],
            ':email'         => $data['email'],
            ':no_hp'         => $data['no_hp'],
            ':alamat'        => $data['alamat'],
            ':jabatan'       => $data['jabatan'],
            ':nama_pengguna' => $data['nama_pengguna'],
            ':kata_sandi'    => password_hash($data['kata_sandi'], PASSWORD_DEFAULT),
            ':status'        => $data['status']
        ]);
    }

    // ===============================
    // Update pengguna (tanpa password)
    // ===============================
    public function update($id, $data) {

        if (!in_array($data['jabatan'], $this->allowedJabatan)) {
            return false;
        }

        $query = "UPDATE {$this->table} SET
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
            ':foto'         => $data['foto'],
            ':email'        => $data['email'],
            ':no_hp'        => $data['no_hp'],
            ':alamat'       => $data['alamat'],
            ':jabatan'      => $data['jabatan'],
            ':status'       => $data['status'],
            ':id'           => $id
        ]);
    }

    // ===============================
    // Update Password
    // ===============================
    public function updatePassword($id, $kata_sandi) {

        $query = "UPDATE {$this->table}
                  SET kata_sandi = :kata_sandi
                  WHERE id_pengguna = :id";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':kata_sandi' => password_hash($kata_sandi, PASSWORD_DEFAULT),
            ':id' => $id
        ]);
    }

    // ===============================
    // Hapus pengguna
    // ===============================
    public function delete($id) {

        $query = "DELETE FROM {$this->table}
                  WHERE id_pengguna = :id";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([':id' => $id]);
    }

    // ===============================
    // Verifikasi Login
    // ===============================
    public function verifyLogin($nama_pengguna, $kata_sandi) {

        $query = "SELECT * FROM {$this->table}
                  WHERE nama_pengguna = :nama_pengguna
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':nama_pengguna' => $nama_pengguna]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false;
        }

        if ($user['status'] !== 'aktif') {
            return false;
        }

        if (!password_verify($kata_sandi, $user['kata_sandi'])) {
            return false;
        }

        unset($user['kata_sandi']); // jangan kirim password ke session

        return $user;
    }
}