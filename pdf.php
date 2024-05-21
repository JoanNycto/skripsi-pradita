<?php
include 'db.php';

// Get the id from the URL parameters
$id = $_GET['id_skripsi'];

// Fetch the skripsi with the given id
$result = mysqli_query($koneksi, "SELECT pdf_skripsi FROM skripsi WHERE id_skripsi = '$id'");
$row = mysqli_fetch_assoc($result);

// Output the PDF file
header('Content-Type: application/pdf');
echo $row['pdf_skripsi'];