<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); 

    // Cek apakah username sudah ada
    $check_username = "SELECT * FROM member WHERE username = '$username'";
    $result = mysqli_query($koneksi, $check_username);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Username sudah digunakan! Silakan pilih username lain.'); window.location.href = 'index.php.php';</script>";
    } else {
        // Query untuk menyimpan data ke tabel member
        $sql = "INSERT INTO member (nama, username, password) VALUES ('$nama', '$username', '$hashed_password')";
        if (mysqli_query($koneksi, $sql)) {
            echo "<script>alert('Registrasi berhasil!'); window.location.href = 'index.php';</script>";
        } else {
            echo "<script>alert('Registrasi gagal! Silakan coba lagi.'); window.location.href = 'index.php';</script>";
        }
    }
}
?>