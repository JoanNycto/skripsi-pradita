<?php
session_start();
include 'db.php';

$judul_skripsi = $_GET['judul_skripsi'];

// Fetch the PDF data
$sql = "SELECT pdf_skripsi FROM skripsi WHERE judul_skripsi = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param('s', $judul_skripsi);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die('Error executing query: ' . $koneksi->error);
}

$row = $result->fetch_assoc();

if (!$row) {
    die('No rows returned from query');
}

$pdfData = $row['pdf_skripsi'];

// Send the headers
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $judul_skripsi . '.pdf"');

// Output the PDF
echo $pdfData;
?>