<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_name'])) {
    header('Location: index.php');
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul_skripsi = $_POST["judul_skripsi"];
    $doi = $_POST["doi"];
    $abstrak = $_POST["abstrak"];
    $keywords = $_POST["keywords"];
    $tgl_pengesahan = $_POST["tgl_pengesahan"];
    $file = $_FILES["pdf_skripsi"];
    $statusSkripsi = $_POST["status_skripsi"];

    $pdfData = addslashes(file_get_contents($file["tmp_name"]));
    $nim = $_SESSION["nim"];

    $sql = "INSERT INTO skripsi (judul_skripsi, doi, abstrak, keywords, tgl_pengesahan, pdf_skripsi, nim, status_skripsi) 
    VALUES ('$judul_skripsi', '$doi', '$abstrak', '$keywords', '$tgl_pengesahan', '$pdfData', '$nim', 'direview')";
    $result = $koneksi->query($sql);

    header("Location: berkas_skripsi.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<?php 
include 'header.php';
?>

<form action="upload_skripsi.php" method="post" enctype="multipart/form-data" class="upload-form">
        <label for="judul_skripsi">Judul Skripsi:</label>
        <input type="text" id="judul_skripsi" name="judul_skripsi" required>

        <label for="doi">DOI:</label>
        <input type="text" id="doi" name="doi" required>

        <label for="abstrak">Abstrak:</label>
        <textarea id="abstrak" name="abstrak" required></textarea>

        <label for="keywords">Keywords:</label>
        <input type="text" id="keywords" name="keywords" required>

        <label for="tgl_pengesahan">Tanggal Pengesahan:</label>
        <input type="date" id="tgl_pengesahan" name="tgl_pengesahan" required>

        <label for="pdf_skripsi">
          <span class="upload-icon">&#128193;</span> <span class="upload-text">Pilih File</span>
          <input type="file" name="pdf_skripsi" id="pdf_skripsi" class="file-input" accept=".pdf" required>
        </label>
        <div>
        <button type="submit" class="upload-butt">Upload</button>
        </div>
</form>
<script>
    var form = document.querySelector('form');
    var uploadButton = document.querySelector('.upload-butt');

    // Disable the button and add the 'disabled' class when the page loads
    uploadButton.disabled = true;
    uploadButton.classList.add('disabled');

    form.addEventListener('input', function() {
        var allFilled = true;
        document.querySelectorAll('input[required], textarea[required]').forEach(function(input) {
            if (!input.value) {
                allFilled = false;
            }
        });

        if (allFilled) {
            uploadButton.disabled = false;
            uploadButton.classList.remove('disabled');
        } else {
            uploadButton.disabled = true;
            uploadButton.classList.add('disabled');
        }
    });

    document.getElementById('pdf_skripsi').addEventListener('change', function(e) {
        var file = e.target.files[0];
        var fileName = file.name;
        var fileSize = (file.size / 1024).toFixed(2); // size in KB
        var uploadText = document.querySelector('.upload-text');

        if (file.size > 400000) {
            uploadText.style.color = 'red';
            uploadButton.disabled = true;
            uploadButton.classList.add('disabled');
        } else {
            uploadText.style.color = 'black';
            uploadButton.disabled = false;
            uploadButton.classList.remove('disabled');
        }

        uploadText.textContent = fileName + ' (' + fileSize + ' KB)';
        document.querySelector('.upload-icon').textContent = '\uD83D\uDCC4'; // Unicode for PDF icon
    });
</script>
</body>
</html>