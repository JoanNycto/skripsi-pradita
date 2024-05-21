<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_name'])) {
    header('Location: index.php');
    exit();
}

$user_name = $_SESSION['user_name'];
$kedudukan = $_SESSION['kedudukan'];
$statusDosen = isset($_SESSION['statusDosen']) ? $_SESSION['statusDosen'] : null;
$nip = isset($_SESSION['nip']) ? $_SESSION['nip'] : null;
$nim = isset($_SESSION['nim']) ? $_SESSION['nim'] : null;
$id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$prodi_mhs = isset($_SESSION['prodi_mhs']) ? $_SESSION['prodi_mhs'] : null;

?>

<!DOCTYPE html>
<html lang="en">
<?php
include 'header.php'
?>
        <h1>Selamat Datang, <?php echo $user_name; ?>!</h1>
        <h2>Dashboard</h2>
    
    <!-- JADWAL SIDANG -->
    <?php
    if ($kedudukan == "mahasiswa") {
        $sql = "SELECT jadwal_sidang.lokasi_sidang, 
        jadwal_sidang.tgl_sidang, 
        jadwal_sidang.waktu_sidang,
        dosen.nama_dosen
        FROM jadwal_sidang
        JOIN penguji_sidang ON jadwal_sidang.id_sidang = penguji_sidang.id_sidang
        JOIN dosen ON penguji_sidang.nip = dosen.nip
        JOIN mahasiswa ON jadwal_sidang.nim = mahasiswa.nim
        WHERE mahasiswa.nim = ?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("s", $_SESSION['nim']); 
        $stmt->bind_result($lokasi_sidang, $tgl_sidang, $waktu_sidang, $nama_dosen);
    } else if ($kedudukan == "dosen") {
        $sql = "SELECT jadwal_sidang.lokasi_sidang, 
        jadwal_sidang.tgl_sidang, 
        jadwal_sidang.waktu_sidang,
        mahasiswa.nama_mhs
        FROM jadwal_sidang
        JOIN penguji_sidang ON jadwal_sidang.id_sidang = penguji_sidang.id_sidang
        JOIN dosen ON penguji_sidang.nip = dosen.nip
        JOIN mahasiswa ON jadwal_sidang.nim = mahasiswa.nim
        WHERE dosen.nip = ?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("s", $_SESSION['nip']); 
        $stmt->bind_result($lokasi_sidang, $tgl_sidang, $waktu_sidang, $nama_mhs);
    } else if ($kedudukan == "admin") {
        $sql = "SELECT jadwal_sidang.lokasi_sidang, 
        jadwal_sidang.tgl_sidang, 
        jadwal_sidang.waktu_sidang,
        dosen.nama_dosen,
        mahasiswa.nama_mhs
        FROM jadwal_sidang
        JOIN penguji_sidang ON jadwal_sidang.id_sidang = penguji_sidang.id_sidang
        JOIN dosen ON penguji_sidang.nip = dosen.nip
        JOIN mahasiswa ON jadwal_sidang.nim = mahasiswa.nim
        ORDER BY jadwal_sidang.tgl_sidang DESC
        LIMIT 5";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_result($lokasi_sidang, $tgl_sidang, $waktu_sidang, $nama_dosen, $nama_mhs);
    }
 
    $stmt->execute();
    $stmt->store_result(); 

    if ($stmt->num_rows > 0) { 
        echo '<div class="container">
        <header>
            <div class="icon-container">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="icon">
                <path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 0 1 7.5 3v1.5h9V3A.75.75 0 0 1 18 3v1.5h.75a3 3 0 0 1 3 3v11.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V7.5a3 3 0 0 1 3-3H6V3a.75.75 0 0 1 .75-.75Zm13.5 9a1.5 1.5 0 0 0-1.5-1.5H5.25a1.5 1.5 0 0 0-1.5 1.5v7.5a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5v-7.5Z" clip-rule="evenodd" />
            </svg>
            </div>
            <h4>Jadwal Sidang</h4>
        </header>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Ruang</th>';

        if ($kedudukan == "mahasiswa" || $kedudukan == "admin") {
            echo '<th>Dosen Penguji</th>';
        } else if ($kedudukan == "dosen" || $kedudukan == "admin") {
            echo '<th>Nama Mahasiswa</th>';
        }

        echo '</tr></thead><tbody>';

        while ($stmt->fetch()) { 
            echo '<tr>
                <td>' . $tgl_sidang . '</td>
                <td>' . $waktu_sidang . '</td>
                <td>' . $lokasi_sidang . '</td>';

            if ($kedudukan == "mahasiswa" || $kedudukan == "admin") {
                echo '<td>' . $nama_dosen . '</td>';
            } else if ($kedudukan == "dosen" || $kedudukan == "admin") {
                echo '<td>' . $nama_mhs . '</td>';
            }

            echo '</tr>';
        }

        echo '</tbody></table></div></div></div>';
    }
$stmt->close();
?>

<!-- LOGBOOK -->
<?php
    if ($kedudukan == "mahasiswa") {
        $sql = "SELECT logbook.tgl_pertemuan, logbook.komentar
        FROM logbook
        JOIN mahasiswa ON logbook.nim=mahasiswa.nim
        WHERE mahasiswa.nim = ?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("s", $_SESSION['nim']);
        $stmt->bind_result($tgl_pertemuan, $komentar);
    } else if ($kedudukan == "dosen") {
        $sql = "SELECT logbook.tgl_pertemuan, logbook.komentar, mahasiswa.nama_mhs
        FROM logbook
        JOIN mahasiswa ON logbook.nim=mahasiswa.nim
        JOIN dosen ON mahasiswa.nip=dosen.nip
        WHERE dosen.nip = ?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("s", $_SESSION['nip']);
        $stmt->bind_result($tgl_pertemuan, $komentar, $nama_mhs);
    } else if ($kedudukan == "admin") {
        $sql = "SELECT logbook.tgl_pertemuan, logbook.komentar, 
        mahasiswa.nama_mhs, dosen.nama_dosen
        FROM logbook
        JOIN mahasiswa ON logbook.nim=mahasiswa.nim
        JOIN dosen ON mahasiswa.nip=dosen.nip
        ORDER BY logbook.tgl_pertemuan DESC
        LIMIT 5";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_result($tgl_pertemuan, $komentar, $nama_mhs, $nama_dosen);
    } 
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows>0){
        echo '<div class="container">
        <header>
            <div class="icon-container">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                  </svg>                  
            </div>
            <h4>Logbook</h4>
        </header>
        <table>
          <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Komentar</th>';

        if ($kedudukan == "admin") {
            echo '<th>Dosen Pembimbing</th>';
        } else if ($kedudukan == "dosen" || $kedudukan == "admin") {
            echo '<th>Nama Mahasiswa</th>';
        }
                
        echo '</tr></thead><tbody>';

        while($stmt->fetch()){
            echo '<tr>
            <td>' . $tgl_pertemuan . '</td>
            <td>' . $komentar . '</td>';

            if ($kedudukan == "admin") {
                echo '<td>' . $nama_dosen . '</td>';
            } else if ($kedudukan == "dosen" || $kedudukan == "admin") {
                echo '<td>' . $nama_mhs . '</td>';
            }

            echo '</tr>';
        }
        echo '</tbody></table></div></div></div>';
    }
    $stmt->close();
    ?>

    <!-- BERKAS SKRIPSI -->
    <?php
    if($kedudukan == "mahasiswa"){
        $sql = "SELECT skripsi.tgl_pengesahan, 
        skripsi.judul_skripsi,
        skripsi.pdf_skripsi,
        mahasiswa.nama_mhs, 
        skripsi.abstrak, skripsi.keywords
        FROM skripsi
        JOIN mahasiswa ON skripsi.nim=mahasiswa.nim
        WHERE mahasiswa.prodi_mhs = ? AND skripsi.status_skripsi = 'diterima'";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("s", $_SESSION['prodi_mhs']);
        $stmt->bind_result($tgl_pengesahan, $judul_skripsi, $pdf_skripsi, $nama_mhs, $abstrak, $keywords);
    } else if ($kedudukan == "dosen" || $kedudukan == "admin") {
        $sql = "SELECT skripsi.tgl_pengesahan,
        skripsi.judul_skripsi,
        skripsi.pdf_skripsi,
        mahasiswa.nama_mhs,
        skripsi.abstrak, skripsi.keywords
        FROM skripsi
        JOIN mahasiswa ON skripsi.nim=mahasiswa.nim";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_result($tgl_pengesahan, $judul_skripsi, $pdf_skripsi, $nama_mhs, $abstrak, $keywords);
    }

    $stmt->execute();
    //$stmt->store_result();
    $result = $stmt->get_result();
    
    echo '<div class="container-home">
    <h4>Berkas Skripsi</h4>
    <div class="search-container">
        <input type="text" placeholder="Search...">
        <button class="search-button">Search</button>
        </div>
    </div>';
    
    if($kedudukan == "mahasiswa"){
        echo 
        '<p style="padding-left: 44px;">' . $_SESSION['prodi_mhs'] . '</p></div>';
    };

    while ($row = $result->fetch_assoc()) {
        $pdfData = $row['pdf_skripsi'];
        $judul_skripsi = $row['judul_skripsi'];
        $tgl_pengesahan = $row['tgl_pengesahan'];
        $nama_mhs = $row['nama_mhs'];

        $imagick = new Imagick();
        $imagick->readImageBlob($pdfData);
        $imagick->setIteratorIndex(0);

        // Convert the PDF to an image format (e.g., jpg)
        $imagick->setImageFormat('jpg');

        // Convert the image to a data URL
        $imageData = base64_encode($imagick);
        $src = 'data:image/jpeg;base64,'.$imageData;

        echo '<div class="container-download">
        <div class="awalDownload">
            <img class="logodok" src="'.$src.'"alt="">
            <div class="desc-log">
                <h4><a href="berkas_skripsi.php?judul_skripsi='. urlencode($judul_skripsi).'">'.$judul_skripsi.'</a></h4>
                <p> Oleh: '.$nama_mhs.'</p>
                <p> Disahkan pada: '.$tgl_pengesahan.'</p>
            </div>
        </div>
        <div class="akhirDownload">
            <a href="download.php?judul_skripsi='. urlencode($judul_skripsi).'" download>
            <div class="icon-container">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
            </div>
            </a>
        </div>
        </div>';
    }
    
    $stmt->close();
    ?>
    <?php
    include 'footer.php';
    ?>
</body>
</html>