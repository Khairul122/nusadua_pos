<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['id_member'])) {
    echo "<script>alert('Anda harus login terlebih dahulu!'); window.location.href='index.php';</script>";
    exit();
}

if (!isset($_GET['kode_pemesanan'])) {
    echo "<script>alert('Kode pemesanan tidak ditemukan!'); window.location.href='riwayat-pesanan.php';</script>";
    exit();
}

$kode_pemesanan = $_GET['kode_pemesanan'];

// Ambil detail pesanan berdasarkan kode_pemesanan
$query = "SELECT p.kode_pemesanan, p.tanggal_pemesanan, pr.nama_produk, p.jumlah, p.harga_satuan, 
                 (p.jumlah * p.harga_satuan) AS total_harga, p.status_pemesanan 
          FROM pemesanan p
          JOIN produk pr ON p.id_produk = pr.id_produk
          WHERE p.kode_pemesanan = '$kode_pemesanan'";
$result = mysqli_query($koneksi, $query);

// Ambil satu baris pertama untuk mendapatkan `tanggal_pemesanan` dan `status_pemesanan`
$row_header = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan - Sistem Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 15px 0;
            width: 100%;
        }

        .content {
            flex: 1;
        }

        .wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        html,
        body {
            height: 100%;
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="index.php">Sistem Penjualan</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#caraPesanModal">Cara Pesan</a></li>
                        <?php if (isset($_SESSION['id_member'])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="riwayat-pemesanan.php">Pesanan Saya</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                    <?= htmlspecialchars($_SESSION['nama']); ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Konten Detail Pesanan -->
        <div class="container mt-5">
            <h1 class="text-center mb-4">Detail Pesanan: <?= htmlspecialchars($kode_pemesanan); ?></h1>

            <div class="card">
                <div class="card-header">
                    <strong>Kode Pemesanan:</strong> <?= htmlspecialchars($kode_pemesanan); ?> <br>
                    <strong>Tanggal Pemesanan:</strong> <?= htmlspecialchars($row_header['tanggal_pemesanan'] ?? '-'); ?>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Nama Produk</th>
                                <th>Jumlah</th>
                                <th>Harga Satuan</th>
                                <th>Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Reset result set untuk mengulang query
                            mysqli_data_seek($result, 0);
                            while ($row = mysqli_fetch_assoc($result)) :
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['nama_produk']); ?></td>
                                    <td><?= htmlspecialchars($row['jumlah']); ?></td>
                                    <td>Rp <?= number_format($row['harga_satuan'], 0, ',', '.'); ?></td>
                                    <td>Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <strong>Status Pesanan:</strong>
                    <span class="badge bg-primary"><?= htmlspecialchars($row_header['status_pemesanan'] ?? 'Tidak Diketahui'); ?></span>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="riwayat-pemesanan.php" class="btn btn-secondary">Kembali</a>
                <a href="c-faktur.php?kode_pemesanan=<?= htmlspecialchars($kode_pemesanan); ?>" target="_blank" class="btn btn-primary">
                    Cetak Faktur
                </a>
            </div>

        </div>

        <!-- Modal Cara Pesan -->
        <div class="modal fade" id="caraPesanModal" tabindex="-1" aria-labelledby="caraPesanLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="caraPesanLabel">Cara Pesan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Berikut adalah cara melakukan pemesanan di Nusa Dua:</p>
                        <ul>
                            <li>Buka website Nusa Dua dan login ke akun Anda.</li>
                            <li>Pilih produk yang ingin Anda beli.</li>
                            <li>Tambahkan produk ke dalam keranjang belanja.</li>
                            <li>Masukkan alamat pengiriman yang benar.</li>
                            <li>Lakukan pembayaran sesuai metode yang tersedia.</li>
                            <li>Konfirmasi pembayaran dan tunggu pengiriman.</li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Footer -->
        <footer class="bg-dark text-white text-center py-3 mt-auto">
            <div class="container">
                <p>&copy; <span id="year"></span> Sistem Penjualan. All Rights Reserved.</p>
            </div>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById("year").innerText = new Date().getFullYear();
    </script>

</body>

</html>