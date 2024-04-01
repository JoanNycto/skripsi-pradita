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
<html>
<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <div class="image-side">
            <img src="https://drive.google.com/thumbnail?id=1xq3BTF-N-LI3ii91P1LbIjlHzTmcK4Ta" class="left-pic">
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