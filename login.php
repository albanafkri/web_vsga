<?php
// Memulai sesi
session_start();

// Jika pengguna sudah login, arahkan ke beranda
if (isset($_SESSION['id_pengguna'])) {
    header("location: beranda.php");
    exit;
}

// Memanggil file koneksi
require_once 'koneksi.php';

// Variabel untuk menyimpan pesan error
$error = '';

// Cek jika ada pesan sukses dari halaman pendaftaran
$pesan_sukses = '';
if (isset($_SESSION['pesan_sukses'])) {
    $pesan_sukses = $_SESSION['pesan_sukses'];
    // Hapus pesan setelah ditampilkan
    unset($_SESSION['pesan_sukses']);
}

// Cek jika form sudah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "Email dan password wajib diisi!";
    } else {
        // Query untuk mencari pengguna berdasarkan email
        $sql = "SELECT id_pengguna, nama_lengkap, password FROM pengguna WHERE email = ?";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            
            // Cek jika email ditemukan
            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $id, $nama, $hashed_password);
                if (mysqli_stmt_fetch($stmt)) {
                    // Verifikasi password
                    if (password_verify($password, $hashed_password)) {
                        // Jika password benar, mulai sesi baru
                        session_start();
                        $_SESSION['id_pengguna'] = $id;
                        $_SESSION['nama_lengkap'] = $nama;
                        
                        // Arahkan ke halaman beranda
                        header("location: beranda.php");
                        exit;
                    } else {
                        $error = "Password yang Anda masukkan salah.";
                    }
                }
            } else {
                $error = "Tidak ada akun yang ditemukan dengan email tersebut.";
            }
        } else {
            $error = "Terjadi kesalahan. Silakan coba lagi.";
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($koneksi);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PT Maju Bersama Furniture</title>
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
        .success-message { color: #27ae60; text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <main class="form-page-container">
        <div class="form-wrapper">
            <h2>Login Akun</h2>
            <?php if (!empty($error)) : ?>
                <p class="error-message"><?php echo $error; ?></p>
            <?php endif; ?>
            <?php if (!empty($pesan_sukses)) : ?>
                <p class="success-message"><?php echo $pesan_sukses; ?></p>
            <?php endif; ?>
            <form action="login.php" method="post">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="form-button">Login</button>
            </form>
            <div class="form-switch-link">
                <p>Belum punya akun? <a href="signup.php">Daftar di sini</a></p>
            </div>
        </div>
    </main>
</body>
</html>
