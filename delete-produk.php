<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id_produk = mysqli_real_escape_string($koneksi, $_GET['id']);

    // Query untuk mendapatkan informasi produk
    $query_get = "SELECT gambar FROM produk WHERE id_produk = '$id_produk'";
    $result_get = mysqli_query($koneksi, $query_get);

    if (mysqli_num_rows($result_get) > 0) {
        $row = mysqli_fetch_assoc($result_get);
        $gambar = $row['gambar'];
        if (!empty($gambar) && file_exists("image/foto_produk/$gambar")) {
            unlink("image/foto_produk/$gambar");
        }
        $query_delete = "DELETE FROM produk WHERE id_produk = '$id_produk'";
        if (mysqli_query($koneksi, $query_delete)) {
            echo "<script>alert('Data produk berhasil dihapus!'); window.location.href = 'produk.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus data produk!'); window.location.href = 'produk.php';</script>";
        }
    } else {
        echo "<script>alert('Produk tidak ditemukan!'); window.location.href = 'produk.php';</script>";
    }
} else {
    echo "<script>alert('ID produk tidak valid!'); window.location.href = 'produk.php';</script>";
}
?>
