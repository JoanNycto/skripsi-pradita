<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_name'])) {
    header('Location: index.php');
    exit();
}
$kedudukan = $_SESSION['kedudukan'];
?>

<!DOCTYPE html>
<html lang="en">
<?php
include 'header.php';
?>
    <div class="full-width-container">   
        <div class="container-home">
            <h4>Berkas Skripsi</h4>
            <div class="search-container">
                <input type="text" placeholder="Search...">
                <button class="search-button">Search</button>
            </div>
        </div>
    </div>

    <!-- <div class="filter-container">
        <button class="filter-button">
          <span class="filter-icon">▾</span> <span class="filter-text">Filter</span>
        </button>
      
        <ul class="filter-list">
          <li><a href="#" data-filter="all">All</a></li>
          <li><a href="#" data-filter="keyword1">Keyword 1</a></li>
          <li><a href="#" data-filter="keyword2">Keyword 2</a></li>
        </ul>
    </div>
      
      <script>
        window.onload = function() {
            var filterItems = document.querySelectorAll('.filter-list a');
            var fileItems = document.querySelectorAll('.file-item');
        
            filterItems.forEach(function(filterItem) {
                filterItem.addEventListener('click', function(event) {
                    event.preventDefault();
                    var filter = this.getAttribute('data-filter');
        
                    fileItems.forEach(function(fileItem) {
                        if (filter === 'all' || fileItem.getAttribute('data-keyword') === filter) {
                            fileItem.style.display = 'block';
                        } else {
                            fileItem.style.display = 'none';
                        }
                    });
                });
            });
        };
      </script> -->

    <?php
    if($kedudukan == "mahasiswa") {
    // Fetch the tgl_sidang data for the current user
    $sql = "SELECT tgl_sidang FROM jadwal_sidang WHERE nim = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('s', $_SESSION['nim']);
    $stmt->execute();
    $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $tgl_sidang = strtotime($row['tgl_sidang']);
            $current_date = strtotime(date('Y-m-d'));

            echo '<div class="upload-button-container">';
            // If the current date is after tgl_sidang, show the "Upload Skripsi" button
            if ($current_date > $tgl_sidang) {
                echo '<a href="upload_skripsi.php" class="upload-button">Upload Skripsi</a>';
            }
            echo '</div>';
        }
    }
    ?>
    
    <!-- Container skripsi -->
    <ul class="file-list" data-keyword="keyword1">
          <?php
        // Check if a judul_skripsi parameter is present in the URL
        if (isset($_GET['judul_skripsi'])) {
        // Get the judul_skripsi from the URL parameters
        $judul_skripsi = $_GET['judul_skripsi'];


        if($kedudukan == "mahasiswa") {
            // Fetch the skripsi data
            $sql = "SELECT * FROM skripsi 
            JOIN mahasiswa ON skripsi.nim=mahasiswa.nim
            WHERE judul_skripsi = ?";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param('s', $judul_skripsi);
        } else {
            $sql = "SELECT * FROM skripsi
            JOIN mahasiswa ON skripsi.nim=mahasiswa.nim
            WHERE judul_skripsi = ?";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param('s', $judul_skripsi);
        }
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

        $imagick = new Imagick();
        $imagick->readImageBlob($pdfData);
        $imagick->setIteratorIndex(0);

        // Convert the PDF to an image format (e.g., jpg)
        $imagick->setImageFormat('jpg');

        // Convert the image to a data URL
        $imageData = base64_encode($imagick);
        $src = 'data:image/jpeg;base64,'.$imageData;

        // Output the image
        echo'<li class="file-item">
        <img src="' . $src . '">';

        echo '<div class="file-details">';
        echo '<h5>' . htmlspecialchars($row['judul_skripsi']) . '</h5>';
        echo '<p>Penulis: ' . htmlspecialchars($row['nama_mhs']) . '</p>';
        echo '<p>Disahkan pada: ' . htmlspecialchars($row['tgl_pengesahan']) . '</p>';
        echo '<p>DOI: ' . htmlspecialchars($row['doi']) . '</p>';
        echo '<p>Abstrak: ' . htmlspecialchars($row['abstrak']) . '</p>';
        echo '<p>Kata Kunci: ' . htmlspecialchars($row['keywords']) . '</p>';

        if ($kedudukan == "admin" && $row['status_skripsi'] == "direview") {
            echo '<button type="button" onclick="location.href=\'status_skripsi.php?action=accept&id=' . $row['id_skripsi'] . '\'">Accept</button>';
            echo '<button type="button" onclick="location.href=\'status_skripsi.php?action=reject&id=' . $row['id_skripsi'] . '\'">Reject</button>';
        }
        echo '</div></li>';

        // $pdf = base64_encode($pdfData);
        // $obj = 'data:application/pdf;base64,' . $pdf;
        // echo '<object data="' . $obj . '" type="application/pdf" width="50%" height="1000px">';

        // Get the number of pages in the PDF
         $numPages = $imagick->getNumberImages();

        // Iterate over each page, starting from the second page
         for ($i = 0; $i < $numPages; $i++) {
             $imagickPage = clone $imagick;
             $imagickPage->setIteratorIndex($i);
             $imagickPage->setImageFormat('jpg');

             $imageData = base64_encode($imagickPage->getImageBlob());
             $src = 'data:image/jpeg;base64,'.$imageData;

             echo '<div class="page">';
             echo '<p>Halaman ' . ($i + 1) . '</p>';
             echo '<img src="' . $src . '">';
             echo '</div>';
             
        }

    } else {
        if ($kedudukan == "mahasiswa") {
            // Fetch and display all skripsi according to the prodi_mhs
            $prodi_mhs = $_SESSION['prodi_mhs'];
            $sql = "SELECT * FROM skripsi 
            JOIN mahasiswa ON skripsi.nim=mahasiswa.nim
            WHERE prodi_mhs = ? AND status_skripsi='diterima'";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param('s', $prodi_mhs);
        } else if ($kedudukan == "dosen") {
            $sql = "SELECT * FROM skripsi 
            JOIN mahasiswa ON skripsi.nim=mahasiswa.nim
            WHERE status_skripsi='diterima'";
            $stmt = $koneksi->prepare($sql);
        } else if ($kedudukan == "admin") {
            $sql = "SELECT * FROM skripsi 
            JOIN mahasiswa ON skripsi.nim=mahasiswa.nim
            ORDER BY CASE WHEN status_skripsi = 'direview' THEN 1 ELSE 2 END, tgl_pengesahan DESC";
            $stmt = $koneksi->prepare($sql);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            die('Error executing query: ' . $koneksi->error);
        }

        while ($row = $result->fetch_assoc()) {
            $pdfData = $row['pdf_skripsi'];
            $status_skripsi = $row['status_skripsi'];
            
            $imagick = new Imagick();
            $imagick->readImageBlob($pdfData);
            $imagick->setIteratorIndex(0);
            $imagick->setImageFormat('jpg');
            $imageData = base64_encode($imagick);
            $src = 'data:image/jpeg;base64,'.$imageData;

            echo'<li class="file-item">
            <img src="' . $src . '">';
            echo '<div class="file-details">';
            echo '<h5><a href="berkas_skripsi.php?judul_skripsi=' . urlencode($row['judul_skripsi']) . '">' . htmlspecialchars($row['judul_skripsi']) . '</a></h5>';
            echo '<p>Penulis: ' . htmlspecialchars($row['nama_mhs']) . '</p>';

            if($kedudukan == "admin" && $status_skripsi == "direview") {
                echo '<p style="color: red;">STATUS PERLU DIPERBAHARUI</p>';
            } else if ($kedudukan == "admin" && $status_skripsi == "diterima") {
                echo '<p style="color: green;">STATUS: DITERIMA</p>';
            } else if ($kedudukan == "admin" && $status_skripsi == "ditolak") {
                echo '<p style="color: brown;">STATUS: DITOLAK</p>';
            }
            echo '</div></li>';
        }
    }
    ?>
    </ul>

<?php
include 'footer.php'
?>
</body>
</html>