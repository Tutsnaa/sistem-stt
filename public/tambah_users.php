<?php
session_start();
require_once '../config/database.php';

$database = new Database();
$conn = $database->connect();

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

$nama     = $_POST['nama'];
$alamat   = $_POST['alamat'];
$no_hp    = $_POST['no_hp'];
$role     = $_POST['role'];
$status   = $_POST['status'];
$username = $_POST['username'];
$password = $_POST['password'];

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$query = "INSERT INTO users 
    (nama, alamat, no_hp, role, status, username, password, tanggal_dibuat) 
    VALUES 
    (:nama, :alamat, :no_hp, :role, :status, :username, :password, NOW())";

$stmt = $conn->prepare($query);

$stmt->bindParam(':nama', $nama);
$stmt->bindParam(':alamat', $alamat);
$stmt->bindParam(':no_hp', $no_hp);
$stmt->bindParam(':role', $role);
$stmt->bindParam(':status', $status);
$stmt->bindParam(':username', $username);
$stmt->bindParam(':password', $hashed_password);

    if ($stmt->execute()) {
        $message = "User berhasil ditambahkan!";
    } else {
        $message = "Gagal menambahkan user!";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Tambah User</title>
</head>

<body>

    <h2>Tambah User</h2>

    <?php if ($message != ""): ?>
    <p style="color:green;"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST">

        <label>Nama</label><br>
        <input type="text" name="nama" required><br><br>

        <label>Alamat</label><br>
        <textarea name="alamat" required></textarea><br><br>

        <label>No HP</label><br>
        <input type="text" name="no_hp" required><br><br>

        <label>Role</label><br>
        <select name="role" required>
            <option value="">-- Pilih Role --</option>
            <option value="Ketua">Ketua</option>
            <option value="Wakil">Wakil</option>
            <option value="Sekretaris 1">Sekretaris 1</option>
            <option value="Sekretaris 2">Sekretaris 2</option>
            <option value="Bendahara 1">Bendahara 1</option>
            <option value="Bendahara 2">Bendahara 2</option>
            <option value="Anggota">Anggota</option>
        </select><br><br>

        <label>Status</label><br>
        <select name="status" required>
            <option value="aktif">Aktif</option>
            <option value="tidak aktif">Tidak Aktif</option>
        </select><br><br>

        <label>Tanggal Daftar</label><br>
        <input type="date" name="tanggal_daftar" required><br><br>

        <label>Username</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Tambah User</button>

    </form>

</body>

</html>