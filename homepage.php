<?php
session_start();
include 'db.php';

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
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <title>SKRIPSI PRADITA</title>
</head>
<body>
    <div class="flex flex-1 py-1 justify-center bg-stone-100 shadow-lg">
        <div class="flex justify-center items-center ">
            <div class="flex items-center divide-x">
                <a href=""><img src="https://drive.google.com/thumbnail?id=1XuLAauZkolE9Sfd_5DYZfLEW4rfyFc1Z" alt="" class="m-6 w-26 h-12 flex flex-none"></a>
                <a href="" class="p-6 hover:bg-slate-200 duration-100 hidden sm:block">Dashboard</a>
                <a href="" class="p-6 hover:bg-slate-200 duration-100 hidden sm:block">Berkas Skripsi</a>
                <a href="" class="p-6 hover:bg-slate-200 duration-100 hidden sm:block">Proposal</a>
                <a href="" class="p-6 hover:bg-slate-200 duration-100 hidden sm:block">Logbook</a>
                <a href="" class="sm:hidden ">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                      </svg>
                </a>

                <div class="relative">
                    <button class="flex items-center justify-center m-6 rounded-md focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="grey" class="w-10 h-10">
                            <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" clip-rule="evenodd" />
                          </svg>

                    </button>
                    <div class="absolute right-0 mt-1 w-48 bg-white rounded-md shadow-lg">
                        <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Profile</a>
                        <a href="logout.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Logout</a>
                    </div>
                </div>
                <script>
                    const dropdownButton = document.querySelector('.relative button');
                    const dropdownMenu = document.querySelector('.relative .absolute');

                    dropdownButton.addEventListener('click', () => {
                        dropdownMenu.classList.toggle('hidden');
                    });
                </script>
            </div>
        </div>
    </div>
    <div class="container mx-auto py-8">
        <h1 class="text-xl">
            <strong >Selamat datang</strong>
        </h1>
        <p class="text-3xl text-gray-600"><?php echo $user_name; ?>!
        </p>
    </div>
    <h1 class="container mx-auto text-2xl py-4">
        <strong>
            Dashboard
        </strong>
    </h1>
    
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
        echo '<div class="container mx-auto py-3">
            <div class="p-4 bg-stone-100 rounded-md shadow-lg">
            <div class="p-4 bg-stone-100 rounded-md shadow-lg">
            <h1 class="container mx-auto py-2 font-medium">
                Jadwal sidang skripsi:
            </h1>
            <table class="w-full container mx-auto p-4  bg-white">
                <thead class="bg-amber-400">
                    <tr>
                        <th class="px-4 py-2">Tanggal</th>
                        <th class="px-4 py-2">Waktu</th>
                        <th class="px-4 py-2">Ruang</th>';

        if ($kedudukan == "mahasiswa" || $kedudukan == "admin") {
            echo '<th class="px-4 py-2">Dosen Penguji</th>';
        } else if ($kedudukan == "dosen" || $kedudukan == "admin") {
            echo '<th class="px-4 py-2">Nama Mahasiswa</th>';
        }

        echo '</tr></thead><tbody class="text-gray-700">';

        while ($stmt->fetch()) { 
            echo '<tr>
                <td class="border px-4 py-2">' . $tgl_sidang . '</td>
                <td class="border px-4 py-2">' . $waktu_sidang . '</td>
                <td class="border px-4 py-2">' . $lokasi_sidang . '</td>';

            if ($kedudukan == "mahasiswa" || $kedudukan == "admin") {
                echo '<td class="border px-4 py-2">' . $nama_dosen . '</td>';
            } else if ($kedudukan == "dosen" || $kedudukan == "admin") {
                echo '<td class="border px-4 py-2">' . $nama_mhs . '</td>';
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
        echo '<div class="container mx-auto py-3">
        <div class="p-4 bg-stone-100 rounded-md shadow-lg">
        <div class="flex justify-between">
            <h1 class="container mx-auto py-2 font-medium	">
                Logbook:
            </h1>
            <a href="" class="flex flex-none">View All</a>
        </div>
        <table class="w-full container mx-auto p-4 shadow-lg bg-white">
            <thead class="bg-amber-400	">
                <tr>
                    <th class="px-4 py-2">Tanggal</th>
                    <th class="px-4 py-2">Komentar</th>';

        if ($kedudukan == "admin") {
            echo '<th class="px-4 py-2">Dosen Pembimbing</th>';
        } else if ($kedudukan == "dosen" || $kedudukan == "admin") {
            echo '<th class="px-4 py-2">Nama Mahasiswa</th>';
        }
                
        echo '</tr></thead><tbody class="text-gray-700">';

        while($stmt->fetch()){
            echo '<tr>
            <td class="border px-4 py-2">' . $tgl_pertemuan . '</td>
            <td class="border px-4 py-2">' . $komentar . '</td>';

            if ($kedudukan == "admin") {
                echo '<td class="border px-4 py-2">' . $nama_dosen . '</td>';
            } else if ($kedudukan == "dosen" || $kedudukan == "admin") {
                echo '<td class="border px-4 py-2">' . $nama_mhs . '</td>';
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
        mahasiswa.nama_mhs, 
        skripsi.abstrak, skripsi.keywords
        FROM skripsi
        JOIN mahasiswa ON skripsi.nim=mahasiswa.nim
        WHERE mahasiswa.prodi_mhs = ?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("s", $_SESSION['prodi_mhs']);
        $stmt->bind_result($tgl_pengesahan, $judul_skripsi, $nama_mhs, $abstrak, $keywords);
    } else if ($kedudukan == "dosen" || $kedudukan == "admin") {
        $sql = "SELECT skripsi.tgl_pengesahan,
        skripsi.judul_skripsi,
        mahasiswa.nama_mhs,
        skripsi.abstrak, skripsi.keywords
        FROM skripsi
        JOIN mahasiswa ON skripsi.nim=mahasiswa.nim";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_result($tgl_pengesahan, $judul_skripsi, $nama_mhs, $abstrak, $keywords);
    }

    $stmt->execute();
    $stmt->store_result();
    
        echo '<div class="container mx-auto py-3">
        <div class="p-4 bg-stone-100 rounded-md shadow-lg">
            <h1 class="container mx-auto py-2 font-medium flex items-center justify-between">
                Berkas Skripsi:
                </h1>';
                if($kedudukan == "mahasiswa"){
                    echo '<p>' . $_SESSION['prodi_mhs'] . '</p>';
                };
            echo '<div class="overflow-x-auto">
                <table class="w-full table-auto bg-white">
                    <thead class="bg-amber-400">
                        <tr>
                            <th class="px-4 py-2">Tanggal Pengesahan</th>
                            <th class="px-4 py-2">Judul Skripsi</th>
                            <th class="px-4 py-2">Nama Mahasiswa</th>
                            <th class="px-4 py-2">Abstrak</th>
                            <th class="px-4 py-2">Keywords</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">';
    
    while ($stmt->fetch()) {
        echo '<tr>
            <td class="border px-4 py-2">' . $tgl_pengesahan . '</td>
            <td class="border px-4 py-2">' . $judul_skripsi . '</td>
            <td class="border px-4 py-2">' . $nama_mhs . '</td>
            <td class="border px-4 py-2">' . $abstrak . '</td>
            <td class="border px-4 py-2">' . $keywords . '</td>
        </tr>';
    }
    
    echo '</tbody></table></div></div></div>';
    
    $stmt->close();
    ?>

    <!-- FOOTER -->
    <footer class="container mx-auto py-4">
        <p class="text-center text-gray-500">© 2022 Your Company. All rights reserved.</p>
    </footer>

</body>
</html>