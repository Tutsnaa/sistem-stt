<?php
session_start();

// Cek login
if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'Anggota') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard Anggota</title>
</head>

<body>
    <h2>Selamat Datang, <?= htmlspecialchars($username) ?> (Anggota)</h2>
    <form method="POST" action="logout.php">
        <button type="submit" name="logout">Logout</button>
    </form>
</body>

</html>