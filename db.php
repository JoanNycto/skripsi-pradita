<?php
$server = "localhost";
$user = "root";
$pw = "";
$db = "skripsi";

$koneksi = mysqli_connect($server, $user, $pw, $db)
    or die(mysqli_error($koneksi));

?>
