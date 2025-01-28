<?php
include 'koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_pemesanan = $_POST['kode_pemesanan'];
    $id_produk_arr = $_POST['id_produk'];
    $id_member = $_POST['id_member'];
    $jumlah_arr = $_POST['jumlah'];
    $harga_satuan_arr = $_POST['harga_satuan'];
    $status_pemesanan = "Menunggu Konfirmasi";
    $tanggal_pemesanan = date("Y-m-d H:i:s");

    if (empty($kode_pemesanan) || empty($id_produk_arr) || empty($id_member) || empty($jumlah_arr) || empty($harga_satuan_arr)) {
        die("<script>alert('Terjadi kesalahan! Ada input yang kosong.'); window.history.back();</script>");
    }

    foreach ($id_produk_arr as $i => $id_produk) {
        $jumlah = $jumlah_arr[$i];
        $harga_satuan = $harga_satuan_arr[$i];
        $total_harga = $jumlah * $harga_satuan;

        $query = "INSERT INTO pemesanan (kode_pemesanan, id_produk, id_member, jumlah, harga_satuan, total_harga, status_pemesanan, tanggal_pemesanan)
                  VALUES ('$kode_pemesanan', '$id_produk', '$id_member', '$jumlah', '$harga_satuan', '$total_harga', '$status_pemesanan', '$tanggal_pemesanan')";

        if (!mysqli_query($koneksi, $query)) {
            echo "<script>alert('Terjadi kesalahan pada query! " . mysqli_error($koneksi) . "'); window.history.back();</script>";
            exit();
        }
    }

    echo "<script>alert('Pesanan berhasil dibuat!'); window.location.href='index.php';</script>";
}
?>
