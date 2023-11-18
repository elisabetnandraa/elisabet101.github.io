<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data Mahasiswa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Data Mahasiswa</h1>

        <!-- Form untuk menambah data mahasiswa -->
        <form action="" method="post">
            <input type="text" name="nim" placeholder="NIM" required>
            <input type="text" name="nama" placeholder="Nama" required>
            <input type="text" name="prodi" placeholder="Program Studi" required>
            <button type="submit" name="tambah">Tambah Data</button>
        </form>

        <!-- Pesan status penambahan data -->
        <div class="message">
            <?php
            $host = "localhost";
            $username = "root";
            $password = "";
            $database = "data_mahasiswa";
            $koneksi = new mysqli($host, $username, $password, $database);

            if ($koneksi->connect_error) {
                die("Koneksi database gagal: " . $koneksi->connect_error);
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah'])) {
                $nim = $_POST['nim'];
                $nama = $_POST['nama'];
                $prodi = $_POST['prodi'];

                $query_tambah = "INSERT INTO mahasiswa (nim, nama, prodi) VALUES ('$nim', '$nama', '$prodi') 
                ON DUPLICATE KEY UPDATE nama = '$nama', prodi = '$prodi'";

                if ($koneksi->query($query_tambah) === TRUE) {
                    echo "Data mahasiswa berhasil ditambahkan.";
                    echo "<script>setTimeout(function(){document.querySelector('.message').style.display = 'none';}, 3000);</script>";
                } else {
                    echo "Error: " . $query_tambah . "<br>" . $koneksi->error;
                }
            }
            ?>
        </div>

        <!-- Tabel untuk menampilkan data mahasiswa -->
        <?php
        $query_tampilkan = "SELECT * FROM mahasiswa";
        $result = $koneksi->query($query_tampilkan);

        if ($result->num_rows > 0) {
            echo "<table border='1'>";
            echo "<tr><th>NIM</th><th>Nama</th><th>Program Studi</th><th>Aksi</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row['nim']."</td>";
                echo "<td>".$row['nama']."</td>";
                echo "<td>".$row['prodi']."</td>";
                echo "<td><a href='edit.php?nim=".$row['nim']."'>Edit</a> | <a href='hapus.php?nim=".$row['nim']."'>Hapus</a></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Tidak ada data mahasiswa.</p>";
        }
        ?>

        <!-- Pencarian Mahasiswa -->
        <h2>Cari Mahasiswa berdasarkan Program Studi</h2>
        <form action="" method="get">
            <select name="prodi">
                <option value="">Pilih Program Studi</option>
                <?php
                $query_program_studi = "SELECT DISTINCT prodi FROM mahasiswa";
                $result_program_studi = $koneksi->query($query_program_studi);

                while ($row = $result_program_studi->fetch_assoc()) {
                    echo "<option value='" . $row['prodi'] . "'>" . $row['prodi'] . "</option>";
                }
                ?>
            </select>
            <button type="submit" name="cari">Cari</button>
        </form>

        <!-- Pencarian data mahasiswa berdasarkan program studi yang dipilih -->
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['cari']) && isset($_GET['prodi'])) {
            $prodi = $koneksi->real_escape_string($_GET['prodi']);

            $query_cari = "SELECT * FROM mahasiswa WHERE prodi = '$prodi'";
            $result_cari = $koneksi->query($query_cari);

            if ($result_cari->num_rows > 0) {
                echo "<h2>Hasil Pencarian</h2>";
                echo "<table border='1'>";
                echo "<tr><th>NIM</th><th>Nama</th><th>Program Studi</th></tr>";
                while ($row = $result_cari->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$row['nim']."</td>";
                    echo "<td>".$row['nama']."</td>";
                    echo "<td>".$row['prodi']."</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>Tidak ditemukan data mahasiswa pada program studi '$prodi'.</p>";
            }
        }

        $koneksi->close();
        ?>
    </div>
</body>
</html>
