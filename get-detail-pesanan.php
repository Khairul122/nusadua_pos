<?php
include 'koneksi.php';

if(isset($_POST['kode_pemesanan'])) {
    $kode_pemesanan = mysqli_real_escape_string($koneksi, $_POST['kode_pemesanan']);
    
    // Query untuk mendapatkan detail pesanan
    $queryPesanan = "SELECT p.*, m.nama AS nama_pembeli 
                     FROM pemesanan p 
                     JOIN member m ON p.id_member = m.id_member 
                     WHERE p.kode_pemesanan = '$kode_pemesanan' 
                     LIMIT 1";
    
    $resultPesanan = mysqli_query($koneksi, $queryPesanan);
    $pesanan = mysqli_fetch_assoc($resultPesanan);
    
    // Query untuk mendapatkan produk dalam pesanan
    $queryProduk = "SELECT pr.nama_produk, p.jumlah, p.harga_satuan, (p.jumlah * p.harga_satuan) AS total_harga 
                    FROM pemesanan p 
                    JOIN produk pr ON p.id_produk = pr.id_produk 
                    WHERE p.kode_pemesanan = '$kode_pemesanan'";
    
    $resultProduk = mysqli_query($koneksi, $queryProduk);
    
    // Output detail pesanan
    echo "<p><strong>Nama Pembeli:</strong> " . htmlspecialchars($pesanan['nama_pembeli']) . "</p>";
    echo "<p><strong>Tanggal Pemesanan:</strong> " . htmlspecialchars($pesanan['tanggal_pemesanan']) . "</p>";
    echo "<p><strong>Status:</strong> " . htmlspecialchars($pesanan['status_pemesanan']) . "</p>";
    
    // Tabel produk
    echo "<table class='table table-bordered'>
            <thead class='table-dark'>
                <tr>
                    <th>Nama Produk</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Total Harga</th>
                </tr>
            </thead>
            <tbody>";
    
    while($produk = mysqli_fetch_assoc($resultProduk)) {
        echo "<tr>
                <td>" . htmlspecialchars($produk['nama_produk']) . "</td>
                <td>" . htmlspecialchars($produk['jumlah']) . "</td>
                <td>Rp " . number_format($produk['harga_satuan'], 0, ',', '.') . "</td>
                <td>Rp " . number_format($produk['total_harga'], 0, ',', '.') . "</td>
              </tr>";
    }
    
    echo "</tbody></table>";
}