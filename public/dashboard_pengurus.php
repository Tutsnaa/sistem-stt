<?php
session_start();

// Cek login
if (!isset($_SESSION['id_user']) || $_SESSION['role'] == 'Anggota') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard Pengurus</title>
</head>

<body>
    <h2>Selamat Datang, <?= htmlspecialchars($username) ?> (<?= htmlspecialchars($role) ?>)</h2>
    <form method="POST" action="login.php">
        <button type="submit" name="logout">Logout</button>
    </form>
</body>

</html>