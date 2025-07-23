<?php
/**
 * File: logout.php
 * Deskripsi: Mengakhiri sesi pengguna (logout) dan mengarahkannya kembali ke halaman utama.
 */

// 1. Memulai sesi
// Ini wajib dilakukan untuk bisa mengakses dan mengelola data sesi.
session_start();

// 2. Menghapus semua variabel sesi
// Langkah ini akan mengosongkan array $_SESSION, menghapus semua data yang tersimpan
// seperti id_pengguna dan nama_lengkap.
$_SESSION = array();

// 3. Menghancurkan sesi
// Perintah ini akan menghapus sesi itu sendiri dari server.
session_destroy();

// 4. Mengarahkan pengguna kembali ke halaman beranda
// Setelah sesi dihancurkan, pengguna akan diarahkan kembali ke beranda.php.
header("location: beranda.php");

// 5. Menghentikan eksekusi skrip
// exit() memastikan tidak ada kode lain yang dieksekusi setelah pengalihan.
exit;
?>
