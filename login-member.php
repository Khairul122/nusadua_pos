<?php
include 'koneksi.php';

session_start(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    $sql = "SELECT * FROM member WHERE username = '$username'";
    $result = mysqli_query($koneksi, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['id_member'] = $user['id_member'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['username'] = $user['username'];

            echo "<script>alert('Login berhasil!'); window.location.href = 'index.php';</script>";
        } else {
            echo "<script>alert('Password salah!'); window.location.href = 'index.php';</script>";
        }
    } else {
        echo "<script>alert('Username tidak ditemukan!'); window.location.href = 'index.php';</script>";
    }
}
?>
