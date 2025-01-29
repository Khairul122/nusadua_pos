<?php
include 'koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = sha1($_POST['password']); // Hash password dengan SHA1 sebelum dibandingkan

    // Cek apakah username ada di database
    $sql = "SELECT * FROM user WHERE username = '$username'";
    $result = mysqli_query($koneksi, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Bandingkan password yang sudah di-hash
        if ($password === $user['password']) { 
            
            // Set session login
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['level'] = $user['level'];

            // Cek level akses
            if ($user['level'] === 'Admin') {
                echo "<script>alert('Login berhasil! Selamat datang, Admin.'); window.location.href = 'home.php';</script>";
            } elseif ($user['level'] === 'Kasir') {
                echo "<script>alert('Login berhasil! Selamat datang, Kasir.'); window.location.href = 'home.php';</script>";
            }elseif ($user['level'] === 'Pimpinan') {
                echo "<script>alert('Login berhasil! Selamat datang, Pimpinan.'); window.location.href = 'home.php';</script>";
            } else {
                echo "<script>alert('Akses ditolak! Anda tidak memiliki izin untuk login.'); window.location.href = 'index.php';</script>";
            }
        } else {
            echo "<script>alert('Password salah!'); window.location.href = 'login.php';</script>";
        }
    } else {
        echo "<script>alert('Username tidak ditemukan!'); window.location.href = 'login-user.php';</script>";
    }
}
?>
