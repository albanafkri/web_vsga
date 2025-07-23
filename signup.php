<?php
// Memulai sesi untuk mengirim pesan sukses
session_start();

// Memanggil file koneksi
require_once 'koneksi.php';

// Variabel untuk menyimpan pesan error
$error = '';

// Cek jika form sudah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_lengkap = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validasi dasar
    if (empty($nama_lengkap) || empty($email) || empty($password)) {
        $error = "Semua kolom wajib diisi!";
    } elseif ($password !== $confirm_password) {
        $error = "Konfirmasi password tidak cocok!";
    } else {
        // Cek apakah email sudah terdaftar
        $sql_cek = "SELECT id_pengguna FROM pengguna WHERE email = ?";
        $stmt_cek = mysqli_prepare($koneksi, $sql_cek);
        mysqli_stmt_bind_param($stmt_cek, "s", $email);
        mysqli_stmt_execute($stmt_cek);
        mysqli_stmt_store_result($stmt_cek);

        if (mysqli_stmt_num_rows($stmt_cek) > 0) {
            $error = "Email ini sudah terdaftar. Silakan gunakan email lain.";
        } else {
            // Hash password sebelum disimpan
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Query untuk menyimpan data pengguna baru
            $sql_insert = "INSERT INTO pengguna (nama_lengkap, email, password) VALUES (?, ?, ?)";
            $stmt_insert = mysqli_prepare($koneksi, $sql_insert);
            mysqli_stmt_bind_param($stmt_insert, "sss", $nama_lengkap, $email, $hashed_password);

            // Eksekusi query
            if (mysqli_stmt_execute($stmt_insert)) {
                // Jika berhasil, set pesan sukses dan arahkan ke halaman login
                $_SESSION['pesan_sukses'] = "Pendaftaran berhasil! Silakan login.";
                header("location: login.php");
                exit;
            } else {
                $error = "Terjadi kesalahan. Silakan coba lagi nanti.";
            }
            mysqli_stmt_close($stmt_insert);
        }
        mysqli_stmt_close($stmt_cek);
    }
    mysqli_close($koneksi);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - PT Maju Bersama Furniture</title>
    <style>
        /* CSS SAMA PERSIS DENGAN SEBELUMNYA */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap');
        body { font-family: 'Poppins', Arial, sans-serif; margin: 0; background-color: #fdfaf6; color: #333; }
        .form-page-container{display:flex;justify-content:center;align-items:center;padding:50px 20px}
        .form-wrapper{background-color:#fff;padding:40px;border-radius:8px;box-shadow:0 4px 20px rgba(0,0,0,.08);width:100%;max-width:400px}
        .form-wrapper h2{text-align:center;margin-top:0;margin-bottom:30px;color:#2c3e50}
        .form-group{margin-bottom:20px}
        .form-group label{display:block;margin-bottom:8px;font-weight:500}
        .form-group input{width:100%;padding:12px;border:1px solid #ddd;border-radius:5px;box-sizing:border-box}
        .form-button{width:100%;padding:12px;background-color:#27ae60;color:#fff;border:none;border-radius:5px;cursor:pointer;font-weight:500;font-size:16px}
        .form-switch-link{text-align:center;margin-top:20px}
        .form-switch-link a{color:#27ae60;font-weight:500}
        .error-message { color: #e74c3c; text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <main class="form-page-container">
        <div class="form-wrapper">
            <h2>Buat Akun Baru</h2>
            <?php if (!empty($error)) : ?>
                <p class="error-message"><?php echo $error; ?></p>
            <?php endif; ?>
            <form action="signup.php" method="post">
                <div class="form-group">
                    <label for="fullname">Nama Lengkap</label>
                    <input type="text" id="fullname" name="fullname" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                 <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="form-button">Daftar</button>
            </form>
            <div class="form-switch-link">
                <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
            </div>
        </div>
    </main>
</body>
</html>
