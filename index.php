<?php
include 'koneksi.php';

$query = "SELECT * FROM produk";
$result = mysqli_query($koneksi, $query);

$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

$queryProduk = "SELECT * FROM produk";
$resultProduk = mysqli_query($koneksi, $queryProduk);


$queryKode = "SELECT kode_pemesanan FROM pemesanan ORDER BY id_pemesanan DESC LIMIT 1";
$resultKode = mysqli_query($koneksi, $queryKode);
$rowKode = mysqli_fetch_assoc($resultKode);
$lastKode = isset($rowKode['kode_pemesanan']) ? (int)substr($rowKode['kode_pemesanan'], 1) + 1 : 1;
$newKode = "P" . str_pad($lastKode, 3, "0", STR_PAD_LEFT);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nusa Dua</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            transition: transform 0.2s;
            cursor: pointer;
            height: 100%;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .product-image {
            height: 200px;
            object-fit: cover;
        }

        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            max-width: 90%;
            width: 500px;
            text-align: center;
        }

        .modal-content img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            color: white;
            font-size: 24px;
            cursor: pointer;
        }

        h1 {
            font-size: 2.5rem;
        }

        h2 {
            font-size: 2rem;
        }

        p {
            font-size: 1rem;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 2rem;
            }

            h2 {
                font-size: 1.5rem;
            }

            p {
                font-size: 0.9rem;
            }

            .modal-content {
                width: 90%;
                padding: 15px;
            }
        }

        @media (max-width: 576px) {
            h1 {
                font-size: 1.75rem;
            }

            h2 {
                font-size: 1.25rem;
            }

            p {
                font-size: 0.8rem;
            }

            .modal-content {
                width: 95%;
                padding: 10px;
            }
        }

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
    <!-- Navbar -->
    <?php session_start(); ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Nusa Dua</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#caraPesanModal">Cara Pesan</a></li>
                    <?php if (isset($_SESSION['id_member'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#pesanModal">Pesan</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?= htmlspecialchars($_SESSION['nama']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="riwayat-pemesanan.php">Pesanan Saya</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
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

    <!-- Modal Login -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Login User Pembeli</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="login-member.php" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                        <button type="button" class="btn btn-link w-100 mt-2" data-bs-toggle="modal" data-bs-target="#registerModal" data-bs-dismiss="modal">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<!-- Modal Registrasi -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerModalLabel">Registrasi User Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="register-member.php" method="post">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Daftar</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <div class="wrapper">
        <div class="content">
            <div class="container mt-5">
                <h1 class="text-center mb-4">Daftar Produk</h1>
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php if (!empty($products)) : ?>
                        <?php foreach ($products as $product) : ?>
                            <div class="col">
                                <div class="card h-100 shadow" onclick="showProductModal(<?= $product['id_produk']; ?>)">
                                    <img src="image/foto_produk/<?= htmlspecialchars($product['gambar']); ?>" class="card-img-top product-image" alt="<?= htmlspecialchars($product['nama_produk']); ?>">

                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($product['nama_produk']); ?></h5>
                                        <p class="card-text"><strong>Harga: Rp <?= number_format($product['harga_satuan'], 0, ',', '.'); ?></strong></p>
                                        <p class="card-text"><?= nl2br(htmlspecialchars($product['deskripsi'])); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="col-12 text-center">
                            <p class="alert alert-warning">Belum ada produk yang tersedia.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="modal fade" id="pesanModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="proses-pemesanan.php" method="post">
                        <div class="modal-header">
                            <h5 class="modal-title">Buat Pesanan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id_member" value="<?= $_SESSION['id_member']; ?>">

                            <div class="mb-3">
                                <label>Kode Pemesanan</label>
                                <input type="text" class="form-control" name="kode_pemesanan" value="<?= $newKode; ?>" readonly>
                            </div>

                            <div id="produkContainer">
                                <div class="produk-item">
                                    <div class="mb-3">
                                        <label>Nama Produk</label>
                                        <select class="form-control id_produk" name="id_produk[]" required onchange="updateHarga(this)">
                                            <option value="">Pilih Produk</option>
                                            <?php while ($row = mysqli_fetch_assoc($resultProduk)): ?>
                                                <option value="<?= $row['id_produk']; ?>" data-harga="<?= $row['harga_satuan']; ?>">
                                                    <?= htmlspecialchars($row['nama_produk']); ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Jumlah</label>
                                        <input type="number" class="form-control jumlah" name="jumlah[]" min="1" required oninput="hitungTotal(this)">
                                    </div>
                                    <div class="mb-3">
                                        <label>Harga Satuan</label>
                                        <input type="text" class="form-control harga_satuan" name="harga_satuan[]" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label>Total Harga</label>
                                        <input type="text" class="form-control total_harga" name="total_harga[]" readonly>
                                    </div>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeProduk(this)">Hapus</button>
                                    <hr>
                                </div>
                            </div>

                            <button type="button" class="btn btn-success btn-sm" onclick="addProduk()">Tambah Produk</button>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Pesan Sekarang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <footer class="bg-dark text-white text-center py-3">
            <div class="container">
                <p>&copy; <span id="year"></span> Nusa Dua All Rights Reserved.</p>
            </div>
        </footer>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById("year").innerText = new Date().getFullYear();
    </script>
    <script>
        function addProduk() {
            let produkContainer = document.getElementById("produkContainer");
            let produkItem = document.querySelector(".produk-item").cloneNode(true);
            produkItem.querySelector(".id_produk").value = "";
            produkItem.querySelector(".jumlah").value = "";
            produkItem.querySelector(".harga_satuan").value = "";
            produkItem.querySelector(".total_harga").value = "";
            produkContainer.appendChild(produkItem);
        }

        function removeProduk(button) {
            let produkContainer = document.getElementById("produkContainer");
            if (produkContainer.children.length > 1) {
                button.parentElement.remove();
            }
        }

        function updateHarga(select) {
            let hargaSatuanInput = select.closest(".produk-item").querySelector(".harga_satuan");
            let totalHargaInput = select.closest(".produk-item").querySelector(".total_harga");
            let jumlahInput = select.closest(".produk-item").querySelector(".jumlah");

            let hargaSatuan = select.options[select.selectedIndex].getAttribute("data-harga");
            hargaSatuanInput.value = hargaSatuan;
            hitungTotal(jumlahInput);
        }

        function hitungTotal(input) {
            let produkItem = input.closest(".produk-item");
            let jumlah = produkItem.querySelector(".jumlah").value;
            let hargaSatuan = produkItem.querySelector(".harga_satuan").value;
            let totalHargaInput = produkItem.querySelector(".total_harga");

            totalHargaInput.value = jumlah * hargaSatuan;
        }
    </script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
        var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
            return new bootstrap.Dropdown(dropdownToggleEl)
        })
    });
</script>


</body>

</html>