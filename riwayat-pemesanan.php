<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['id_member'])) {
    echo "<script>alert('Anda harus login terlebih dahulu!'); window.location.href='index.php';</script>";
    exit();
}

$id_member = $_SESSION['id_member'];

// Ambil data pesanan berdasarkan kode_pemesanan, tidak menampilkan duplikasi
$query = "SELECT DISTINCT p.kode_pemesanan, p.tanggal_pemesanan, p.status_pemesanan
          FROM pemesanan p
          WHERE p.id_member = '$id_member'
          ORDER BY p.tanggal_pemesanan DESC";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - Sistem Penjualan</title>
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
                                <a class="nav-link active" href="riwayat-pemesanan.php">Pesanan Saya</a>
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

        <!-- Konten Riwayat Pesanan -->
        <div class="container mt-5">
            <h1 class="text-center mb-4">Riwayat Pesanan Saya</h1>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Kode Pemesanan</th>
                        <th>Tanggal Pemesanan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0) : ?>
                        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                            <tr>
                                <td><?= htmlspecialchars($row['kode_pemesanan']); ?></td>
                                <td><?= htmlspecialchars($row['tanggal_pemesanan']); ?></td>
                                <td>
                                    <span class="badge bg-primary"><?= htmlspecialchars($row['status_pemesanan']); ?></span>
                                </td>
                                <td>
                                    <a href="detail-pesanan.php?kode_pemesanan=<?= $row['kode_pemesanan']; ?>" class="btn btn-info btn-sm">Lihat Detail</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4" class="text-center">Belum ada pesanan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Auto Update Tahun -->
    <script>
        document.getElementById("year").innerText = new Date().getFullYear();
    </script>

</body>

</html>