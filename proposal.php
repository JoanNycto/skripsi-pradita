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

<?php
if ($kedudukan == "mahasiswa") {
    $sql = "SELECT proposal.tgl_submit, 
    ket_proposal.status_proposal, 
    dosen.nama_dosen, 
    proposal.pdf_proposal,
    proposal.id_proposal
    FROM proposal
    JOIN ket_proposal ON proposal.id_proposal=ket_proposal.id_proposal
    JOIN dosen ON ket_proposal.nip=dosen.nip
    JOIN mahasiswa ON proposal.nim=mahasiswa.nim
    WHERE mahasiswa.nim = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("s", $_SESSION['nim']);
    $stmt->bind_result($tgl_submit, $status_proposal, $nama_dosen, $pdf_proposal, $id_proposal);
} else if ($kedudukan == "dosen") {
    $sql = "SELECT proposal.tgl_submit, 
    ket_proposal.status_proposal, 
    mahasiswa.nama_mhs, 
    proposal.pdf_proposal,
    proposal.id_proposal
    FROM proposal
    JOIN ket_proposal ON proposal.id_proposal=ket_proposal.id_proposal
    JOIN mahasiswa ON proposal.nim=mahasiswa.nim
    WHERE ket_proposal.nip = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("s", $_SESSION['nip']);
    $stmt->bind_result($tgl_submit, $status_proposal, $nama_mhs, $pdf_proposal, $id_proposal);
} else if ($kedudukan == "admin") {
    $sql = "SELECT proposal.tgl_submit, 
    ket_proposal.status_proposal, 
    mahasiswa.nama_mhs, 
    dosen.nama_dosen, 
    proposal.pdf_proposal,
    proposal.id_proposal
    FROM proposal
    JOIN ket_proposal ON proposal.id_proposal=ket_proposal.id_proposal
    JOIN mahasiswa ON proposal.nim=mahasiswa.nim
    JOIN dosen ON ket_proposal.nip=dosen.nip";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_result($tgl_submit, $status_proposal, $nama_mhs, $nama_dosen, $pdf_proposal, $id_proposal);
}

$stmt->execute();
$stmt->store_result();

echo '<div class="full-width-container">
    <div class="container-home">
        <h4>Proposal</h4>
        </div>
    </div>';

    if ($kedudukan == "mahasiswa") {
        include 'upload_proposal.php';
    } 

    echo '<div class="container">
    <table>
        <thead>
            <tr>
                <th>Tanggal Submit</th>';
                if ($kedudukan == "dosen" || $kedudukan == "admin") {
                    echo '<th>Nama Mahasiswa</th>';
                } else if ($kedudukan == "mahasiswa" || $kedudukan == "admin") {
                    echo '<th>Nama Dosen</th>';
                }
                echo '<th>PDF Proposal</th>
                <th>Status Proposal</th>';

                while ($stmt->fetch()){
                    $_SESSION['id_proposal'] = $id_proposal;
                    $pdf = $pdf_proposal;
                    //$imagick = new Imagick();
                    //$imagick->readImageBlob($pdf);
                    //$imagick->setImageFormat('jpg');
                    //$imageData = base64_encode($imagick);
                    $imageData = base64_encode($pdf);
                    $src = 'data:application/pdf;base64,' . $imageData;

                    echo '<tr>
                    <td>' . $tgl_submit . '</td>';
                    if ($kedudukan == "dosen" || $kedudukan == "admin") {
                        echo '<td>' . $nama_mhs . '</td>';
                    } else if ($kedudukan == "mahasiswa" || $kedudukan == "admin") {
                        echo '<td>' . $nama_dosen . '</td>';
                    }
                    echo '<td><object data="' . $src . '
                    " type="application/pdf" width="600" height="400">
                    </object></td>';

                    if ($kedudukan == "dosen" && $status_proposal == "diproses") {
                        echo '<td>
                        <form method="post" action="update_status.php">
                        <input type="hidden" name="id" value="'.$id_proposal.'">
                        <select name="status_proposal" id="status_proposal" onchange="this.form.submit()">
                            <option value="diproses">Diproses</option>

                            <option value="diterima tanpa revisi"'.
                            ($status_proposal == "diterima tanpa revisi" ? "selected" : "").'>
                            Diterima Tanpa Revisi</option>

                            <option value="diterima dengan revisi"'.
                            ($status_proposal == "diterima dengan revisi" ? "selected" : "").'>
                            Diterima Dengan Revisi</option>

                            <option value="ditolak"'.
                            ($status_proposal == "ditolak" ? "selected" : "").'>
                            Ditolak</option>
                        </select>
                        </form>
                        </td>';
                    } else {
                    echo '<td>' . $status_proposal . '</td>';
                    }
                    echo '</tr>';
                }
            echo '</tr>
        </thead>
    </table>';

?>
</html>