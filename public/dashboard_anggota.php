<?php
session_start();

// Cek login & jabatan anggota
if (!isset($_SESSION['user']) || $_SESSION['user']['jabatan'] !== 'anggota') {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard Anggota</title>
</head>

<body>

    <h2>
        Selamat Datang,
        <?= htmlspecialchars($user['nama_lengkap']) ?> (Anggota)
    </h2>

    <form method="POST" action="logout.php">
        <button type="submit">Logout</button>
    </form>

</body>

</html>