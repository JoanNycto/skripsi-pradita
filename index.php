<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT users.*, 
    dosen.nip, dosen.status_dosen, dosen.nama_dosen, 
    admin.nama_admin, admin.id,
    mahasiswa.nim, mahasiswa.nama_mhs, mahasiswa.prodi_mhs
    FROM users
    LEFT JOIN dosen ON users.username=dosen.username
    LEFT JOIN admin ON users.username=admin.username
    LEFT JOIN mahasiswa ON users.username=mahasiswa.username
    WHERE users.username='$username' AND users.password='$password'";
    $result = $koneksi->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION['kedudukan'] = $row['kedudukan'];
        $kedudukan = $_SESSION['kedudukan'];

        if ($kedudukan == "admin") {
            $_SESSION['user_name'] = $row['nama_admin'];
            $_SESSION['id'] = $row['id'];
            header("Location: homepage.php");
            exit();
        }
        else if ($kedudukan == "dosen"){
            $_SESSION['nip'] = $row['nip'];
            $_SESSION['statusDosen'] = $row['status_dosen'];
            $_SESSION['user_name'] = $row['nama_dosen'];
            header("Location: homepage.php");
            exit();
        }
        else if ($kedudukan == "mahasiswa") {
            $_SESSION['nim'] = $row['nim'];
            $_SESSION['user_name'] = $row['nama_mhs'];
            $_SESSION['prodi_mhs'] = $row['prodi_mhs'];
            header("Location: homepage.php");
            exit();
        } 
    } else {
        $loginError = "Invalid username or password.";
    }
}

$koneksi->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <title>Login Page</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="login-container">
        <div class="image-side">
            <img src="images/pradita.jpg" width=50%>
        </div>
        <div class="login-side">
            <img src="https://drive.google.com/thumbnail?id=1XuLAauZkolE9Sfd_5DYZfLEW4rfyFc1Z" alt="Logo" class="logo">
            <div class="login-box">
                <h2>Login</h2>
                <?php if (isset($loginError)) : ?>
                    <p class="error-message"><?php echo $loginError; ?></p>
                <?php endif; ?>
                <form method="POST" action="index.php">
                    <div class="input-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" placeholder="Masukkan username" required>
                    </div>

                    <div class="input-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" placeholder="Masukkan password" required>
                    </div>

                    <div class="input-group">
                        <input type="submit" value="Login">
                    </div>

                    <p>Belum punya akun? <a href="signup.php">Daftar disini</a></p>
                </form>
            </div>
        </div>
    </div>
    </body>
</html>