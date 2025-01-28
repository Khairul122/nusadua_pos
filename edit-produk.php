<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_produk = mysqli_real_escape_string($koneksi, $_POST['id_produk']);
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
        $query = "UPDATE produk SET 
                    nama_produk = '$nama_produk', 
                    merek = '$merek', 
                    harga_satuan = '$harga_satuan', 
                    deskripsi = '$deskripsi',
                    stok = '$stok', 
                    satuan = '$satuan', 
                    gambar = '$gambar' 
                  WHERE id_produk = '$id_produk'";
    } else {
        $query = "UPDATE produk SET 
                    nama_produk = '$nama_produk', 
                    merek = '$merek', 
                    harga_satuan = '$harga_satuan', 
                    deskripsi = '$deskripsi',
                    stok = '$stok', 
                    satuan = '$satuan' 
                  WHERE id_produk = '$id_produk'";
    }

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Data produk berhasil diperbarui!'); window.location.href = 'produk.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data produk!'); window.location.href = 'produk.php';</script>";
    }
}
?>
