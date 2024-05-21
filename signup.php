<?php
session_start();
include 'db.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $nama = $_POST["nama"];
    $nim = $_POST["nim"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $prodi_mhs = $_POST["prodi_mhs"];

    $sql = "INSERT INTO users (username, password, kedudukan) 
    VALUES ('$username', '$password', 'mahasiswa')";
    $result = $koneksi->query($sql);

    $sql = "INSERT INTO mahasiswa (username, nama_mhs, nim, email_mhs, prodi_mhs) 
    VALUES ('$username', '$nama', '$nim', '$email', '$prodi_mhs')";
    $result = $koneksi->query($sql);

    header("Location: index.php");
    exit();
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
    <title>Sign Up Page</title>
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
                <h2>Sign Up</h2>
                <?php if (isset($loginError)) : ?>
                    <p class="error-message"><?php echo $loginError; ?></p>
                <?php endif; ?>
            <form method="POST" action="signup.php">
                <div class="input-group">
                    <label for="nama">Nama</label>
                    <input type="text" name="nama" id="nama" required><br>
                </div>

                <div class="input-group">
                    <label for="nim">NIM</label>
                    <input type="text" name="nim" id="nim" required><br>
                </div>
                
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" required><br>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required><br>
                </div>

                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" required><br>
                </div>

                <div class="input-group">
                    <label for="prodi_mhs">Program Studi</label>
                    <select name="prodi_mhs" id="prodi_mhs" required>
                    <option value="">-- Pilih Program Studi --</option>
                    <option value="Arsitektur">Arsitektur</option>
                    <option value="Desain Interior">Desain Interior</option>
                    <option value="Manajemen Retail">Manajemen Retail</option>
                    <option value="Sistem Informasi">Sistem Informasi</option>
                    <option value="Perencanaan Wilayah Kota">Perencanaan Wilayah Kota</option>
                    <option value="Desain Komunikasi Visual">Desain Komunikasi Visual</option>
                    <option value="Manajemen Bisnis">Manajemen Bisnis</option>
                    <option value="Pariwisata">Pariwisata</option>
                    <option value="Teknik Sipil">Teknik Sipil</option>
                    <option value="Akuntansi">Akuntansi</option>
                    <option value="Teknik Informatika">Teknik Informatika</option>
                    </select><br><br>
                </div>

                <div class="input-group">
                    <input type="submit" value="Sign Up">
                </div>

                <p> Sudah punya akun? <a href="index.php">Login disini</a></p>
        </form>
    </div>
</body>
</html>
