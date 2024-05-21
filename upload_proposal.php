<?php

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $tgl_submit = date('Y-m-d');
    $file = $_FILES["pdf_proposal"];
    $status_proposal = 'diproses';

    $pdfData = addslashes(file_get_contents($file["tmp_name"]));
    $nim = $_SESSION["nim"];

    $sql = "SELECT dosen.nip FROM dosen
    JOIN mahasiswa ON dosen.nip=mahasiswa.nip
    WHERE mahasiswa.nim = '$nim'";
    $result = $koneksi->query($sql);
    $row = $result->fetch_assoc();
    $nip = $row['nip'];

    $sql = "INSERT INTO proposal (tgl_submit, pdf_proposal, nim)
    VALUES ('$tgl_submit', '$pdfData', '$nim')";
    $result = $koneksi->query($sql);

    $id_proposal = $koneksi->insert_id;

    $sql = "INSERT INTO ket_proposal (id_proposal, status_proposal, nip)
    VALUES ('$id_proposal', '$status_proposal', '$nip')";
    $result = $koneksi->query($sql);

    header("Location: proposal.php");
    exit();
}
?>

<form action="proposal.php" method="post" enctype="multipart/form-data" class="upload-form-proposal">
        <label for="tgl_submit">Tanggal Submit:</label>
        <input type="date" id="tgl_submit" name="tgl_submit"
        value="<?php echo date('Y-m-d'); ?>" disabled>

        <div>
        <label for="pdf_proposal">
          <span class="upload-icon">&#128193;</span> <span class="upload-text">Pilih File</span>
          <input type="file" name="pdf_proposal" id="pdf_proposal" class="file-input" accept=".pdf" required>
        </label>
        </div>
        <div>
        <button type="submit" class="upload-butt">Upload</button>
        </div> 
</form>
<script>
    var form = document.querySelector('form');
    var uploadButton = document.querySelector('.upload-butt');
    
    document.getElementById('pdf_proposal').addEventListener('change', function(e) {
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