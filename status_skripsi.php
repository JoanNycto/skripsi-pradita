<?php
include 'db.php'; // Include your database connection file

// Check if 'id_skripsi' and 'action' parameters are set
if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = $_GET['id']; // Get the id from the URL
    $action = $_GET['action']; // Get the action from the URL

    // Determine the status based on the action
    $status_skripsi = $action == 'accept' ? 'diterima' : 'ditolak';

    // Prepare and execute the SQL query
    $sql = "UPDATE skripsi SET status_skripsi=? WHERE id_skripsi=?";
    $stmt = $koneksi->prepare($sql);
    
    // Check if the statement was prepared successfully
    if ($stmt) {
        $stmt->bind_param('si', $status_skripsi, $id);

        if ($stmt->execute()) {
            // Redirect back to berkas_skripsi.php if the update was successful
            header("Location: berkas_skripsi.php");
            exit();
        } else {
            // Print error if execution failed
            echo 'Error: ' . $stmt->error;
        }
    } else {
        // Print error if statement preparation failed
        echo 'Error: ' . $koneksi->error;
    }
} else {
    // Print error if parameters are not set
    echo 'Error: id_skripsi or action parameter not set';
}
?>
