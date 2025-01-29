<?php
include 'koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['kode'])) {
    $kode_pemesanan = mysqli_real_escape_string($koneksi, $_GET['kode']);

    $check_query = "SELECT * FROM pemesanan WHERE kode_pemesanan = '$kode_pemesanan'";
    $check_result = mysqli_query($koneksi, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $delete_query = "DELETE FROM pemesanan WHERE kode_pemesanan = '$kode_pemesanan'";
        if (mysqli_query($koneksi, $delete_query)) {
            echo "<script>alert('Pesanan berhasil dihapus!'); window.location.href = 'kasir.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus pesanan!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Pesanan tidak ditemukan!'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Akses tidak diizinkan!'); window.location.href = 'kasir.php';</script>";
}
?>
