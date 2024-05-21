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
include 'header.php';
?>
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
        ORDER BY logbook.tgl_pertemuan DESC";
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