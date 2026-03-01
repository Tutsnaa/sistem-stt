<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem STT</title>
    <link rel="stylesheet" href="../asset/css/login.css">
</head>

<body>
    <div class="login-container">
        <h2>Login Sistem STT</h2>

        <?php
        if (isset($_SESSION['error'])) {
            echo "<p class='error'>" . htmlspecialchars($_SESSION['error']) . "</p>";
            unset($_SESSION['error']);
        }
        ?>

        <form action="../src/controller/AuthController.php" method="POST">

            <label>Nama Pengguna</label>
            <input type="text" name="nama_pengguna" placeholder="Masukkan nama pengguna" required>

            <label>Kata Sandi</label>
            <input type="password" name="kata_sandi" placeholder="Masukkan kata sandi" required>

            <button type="submit">Login</button>

        </form>
    </div>
</body>

</html>