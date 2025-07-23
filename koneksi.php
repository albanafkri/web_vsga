<?php
// --- Konfigurasi Database ---
$db_host = 'localhost'; // Biasanya 'localhost'
$db_user = 'root';      // User default XAMPP/Laragon
$db_pass = '';          // Password default XAMPP/Laragon kosong
$db_name = 'vsga';      // Nama database Anda

// --- Membuat Koneksi ---
$koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// --- Cek Koneksi ---
if (!$koneksi) {
    // Jika koneksi gagal, hentikan skrip dan tampilkan pesan error
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>
