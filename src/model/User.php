<?php
require_once __DIR__ . '/../../config/database.php';

/* ==============================
   MODEL USER
   Menghandle semua operasi database untuk user
   ============================== */
class User
{
    private $conn;
    private $table = 'users';

    // ===== Properti User =====
    public $id_user;
    public $nama;
    public $alamat;
    public $no_hp;
    public $role;
    public $status;
    public $username;
    public $password;
    public $tanggal_dibuat;

    /* ==============================
       CONSTRUCTOR
       Membuat koneksi database
       ============================== */
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    /* ==============================
       GET ALL USERS
       Mengambil semua data user
       ============================== */
    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY id_user DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /* ==============================
       GET USER BY ID
       Mengambil data user berdasarkan ID
       ============================== */
    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id_user = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* ==============================
       CREATE USER
       Menambahkan user baru ke database
       ============================== */
    public function create()
    {
        $query = "INSERT INTO " . $this->table . "
            (nama, alamat, no_hp, role, status, username, password, tanggal_dibuat)
            VALUES (:nama, :alamat, :no_hp, :role, :status, :username, :password, NOW())";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama', $this->nama);
        $stmt->bindParam(':alamat', $this->alamat);
        $stmt->bindParam(':no_hp', $this->no_hp);
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password);

        return $stmt->execute();
    }

    /* ==============================
       UPDATE USER
       Mengubah data user berdasarkan ID
       ============================== */
    public function update($id)
    {
        $query = "UPDATE " . $this->table . " SET
            nama = :nama,
            alamat = :alamat,
            no_hp = :no_hp,
            role = :role,
            status = :status,
            username = :username";

        if (!empty($this->password)) {
            $query .= ", password = :password"; // update password jika diisi
        }

        $query .= " WHERE id_user = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama', $this->nama);
        $stmt->bindParam(':alamat', $this->alamat);
        $stmt->bindParam(':no_hp', $this->no_hp);
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':username', $this->username);

        if (!empty($this->password)) {
            $stmt->bindParam(':password', $this->password);
        }

        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    /* ==============================
       DELETE USER
       Menghapus user berdasarkan ID
       ============================== */
    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id_user = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}