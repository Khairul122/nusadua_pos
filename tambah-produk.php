<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_produk = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
    $merek = mysqli_real_escape_string($koneksi, $_POST['merek']);
    $harga_satuan = mysqli_real_escape_string($koneksi, $_POST['harga_satuan']);
    $stok = mysqli_real_escape_string($koneksi, $_POST['stok']);
    $satuan = mysqli_real_escape_string($koneksi, $_POST['satuan']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']); // Ambil deskripsi

    $gambar = $_FILES['gambar']['name'];
    $tmp_name = $_FILES['gambar']['tmp_name'];
    $target_dir = "image/foto_produk/";
    $target_file = $target_dir . $gambar;

    if ($gambar) {
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        move_uploaded_file($tmp_name, $target_file);
    }

    $query = "INSERT INTO produk (nama_produk, merek, harga_satuan, deskripsi, stok, satuan, gambar) 
              VALUES ('$nama_produk', '$merek', '$harga_satuan', '$deskripsi', '$stok', '$satuan', '$gambar')";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Data produk berhasil ditambahkan!'); window.location.href = 'produk.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data produk!'); window.location.href = 'produk.php';</script>";
    }
}
?>
