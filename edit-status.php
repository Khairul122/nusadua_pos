<?php
include 'koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['kode_pemesanan']) || !isset($_POST['status'])) {
        echo "<script>alert('Data tidak lengkap!'); window.history.back();</script>";
        exit();
    }

    $kode_pemesanan = mysqli_real_escape_string($koneksi, $_POST['kode_pemesanan']);
    $status = mysqli_real_escape_string($koneksi, $_POST['status']);

    // Validasi status yang diperbolehkan
    $allowed_status = ['Menunggu Konfirmasi', 'Selesai', 'Batal'];
    if (!in_array($status, $allowed_status)) {
        echo "<script>alert('Status tidak valid!'); window.history.back();</script>";
        exit();
    }

    // Query update status pemesanan
    $query = "UPDATE pemesanan SET status_pemesanan = '$status' WHERE kode_pemesanan = '$kode_pemesanan'";
    
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Status berhasil diperbarui!'); window.location.href = 'kasir.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat memperbarui status!'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Akses tidak diizinkan!'); window.location.href = 'kasir.php';</script>";
}
?>
