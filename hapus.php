<?php
// Koneksi ke database
$host = "localhost";
$username = "root";
$password = ""; // Isi dengan password database Anda jika ada
$database = "data_mahasiswa";
$koneksi = new mysqli('localhost', 'root', '', 'data_mahasiswa');

// Periksa koneksi
if ($koneksi->connect_error) {
    die("Koneksi database gagal: " . $koneksi->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['nim'])) {
    $nim = $_GET['nim'];

    // Query hapus data mahasiswa berdasarkan NIM
    $query_hapus = "DELETE FROM mahasiswa WHERE nim = '$nim'";
    
    if ($koneksi->query($query_hapus) === TRUE) {
        header("Location: index.php"); // Redirect kembali ke halaman utama setelah berhasil dihapus
        exit();
    } else {
        echo "Error: " . $query_hapus . "<br>" . $koneksi->error;
    }
}
?>
