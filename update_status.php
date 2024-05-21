<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id']) && isset($_POST['status_proposal'])) {
        $id_proposal = $_POST['id'];
        $status_proposal = $_POST['status_proposal'];

        $sql = "UPDATE ket_proposal SET status_proposal = ? WHERE id_proposal = ?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param('si', $status_proposal, $id_proposal);

        if ($stmt->execute()) {
            header('Location: proposal.php');
        } else {
            echo 'Error: ' . $stmt->error;
        }
    } else {
        echo 'Error: id or status_proposal not set in POST request';
    }
}
?>