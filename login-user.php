<?php
include 'koneksi.php';

session_start(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    $sql = "SELECT * FROM user WHERE username = '$username'";
    $result = mysqli_query($koneksi, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (sha1($password) === $user['password']) { 
            if ($user['level'] === 'Admin') {
                $_SESSION['id_user'] = $user['id_user'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['level'] = $user['level'];

                echo "<script>alert('Login berhasil! Selamat datang, Admin.'); window.location.href = 'home.php';</script>";
            } else {
                echo "<script>alert('Akses ditolak! Hanya admin yang dapat login.'); window.location.href = 'index.php';</script>";
            }
        } else {
            echo "<script>alert('Password salah!'); window.location.href = 'login.php';</script>";
        }
    } else {
        echo "<script>alert('Username tidak ditemukan!'); window.location.href = 'login-user.php';</script>";
    }
}
?>
