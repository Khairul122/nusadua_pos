<?php
include 'template/header.php';
include 'koneksi.php';

$level = isset($_SESSION['level']) ? $_SESSION['level'] : null;

$query_member = "SELECT COUNT(*) as total_member FROM member";
$result_member = mysqli_query($koneksi, $query_member);
$total_member = mysqli_fetch_assoc($result_member)['total_member'];

$query_produk = "SELECT COUNT(*) as total_produk FROM produk";
$result_produk = mysqli_query($koneksi, $query_produk);
$total_produk = mysqli_fetch_assoc($result_produk)['total_produk'];

$query_pemesanan_count = "SELECT COUNT(*) as total_pemesanan FROM pemesanan";
$result_pemesanan_count = mysqli_query($koneksi, $query_pemesanan_count);
$total_pemesanan = mysqli_fetch_assoc($result_pemesanan_count)['total_pemesanan'];

$query_produk_table = "SELECT * FROM produk ORDER BY nama_produk ASC";
$result_produk_table = mysqli_query($koneksi, $query_produk_table);

$where = [];
if (!empty($_GET['tanggal'])) {
    $where[] = "DATE(p.tanggal_pemesanan) = '" . mysqli_real_escape_string($koneksi, $_GET['tanggal']) . "'";
}
if (!empty($_GET['bulan'])) {
    $where[] = "MONTH(p.tanggal_pemesanan) = '" . mysqli_real_escape_string($koneksi, $_GET['bulan']) . "'";
}
if (!empty($_GET['tahun'])) {
    $where[] = "YEAR(p.tanggal_pemesanan) = '" . mysqli_real_escape_string($koneksi, $_GET['tahun']) . "'";
}
$where_clause = !empty($where) ? " WHERE " . implode(" AND ", $where) : "";

$query_pemesanan = "
    SELECT p.kode_pemesanan, p.tanggal_pemesanan, COALESCE(p.status_pemesanan, 'Tidak Diketahui') AS status_pemesanan, p.jumlah, 
           p.harga_satuan, p.total_harga, m.nama AS nama_pembeli, pr.nama_produk
    FROM pemesanan p
    JOIN member m ON p.id_member = m.id_member
    JOIN produk pr ON p.id_produk = pr.id_produk
    " . $where_clause . "
    ORDER BY p.tanggal_pemesanan DESC";

$result_pemesanan = mysqli_query($koneksi, $query_pemesanan);
$no = 1;
?>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include 'template/navbar.php'; ?>
        <?php include 'template/sidebar.php'; ?>
        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Dashboard</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="app-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card shadow">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Total Member</h5>
                                </div>
                                <div class="card-body">
                                    <h3 class="text-center"><?= $total_member; ?> Member</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card shadow">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">Total Produk</h5>
                                </div>
                                <div class="card-body">
                                    <h3 class="text-center"><?= $total_produk; ?> Produk</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card shadow">
                                <div class="card-header bg-warning text-white">
                                    <h5 class="mb-0">Total Pemesanan</h5>
                                </div>
                                <div class="card-body">
                                    <h3 class="text-center"><?= $total_pemesanan; ?> Pemesanan</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Laporan Pemesanan -->
                    <div class="card mt-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title">Laporan Pemesanan</h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="tanggal">Tanggal</label>
                                        <input type="date" class="form-control" name="tanggal" id="tanggal" value="<?= isset($_GET['tanggal']) ? $_GET['tanggal'] : ''; ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="bulan">Bulan</label>
                                        <select class="form-control" name="bulan" id="bulan">
                                            <option value="">Pilih Bulan</option>
                                            <?php for ($i = 1; $i <= 12; $i++): ?>
                                                <option value="<?= $i; ?>" <?= (isset($_GET['bulan']) && $_GET['bulan'] == $i) ? 'selected' : ''; ?>><?= $i; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="tahun">Tahun</label>
                                        <input type="number" class="form-control" name="tahun" id="tahun" placeholder="Masukkan Tahun" value="<?= isset($_GET['tahun']) ? $_GET['tahun'] : ''; ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="nama_pimpinan">Nama Pimpinan</label>
                                        <input type="text" class="form-control" name="nama_pimpinan" id="nama_pimpinan" placeholder="Masukkan Nama Pimpinan" value="<?= isset($_GET['nama_pimpinan']) ? $_GET['nama_pimpinan'] : ''; ?>">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Filter</button>
                                <button type="button" class="btn btn-success mt-3"
                                    onclick="window.open('c-pemesanan.php?'+new URLSearchParams(new FormData(this.form)).toString(), '_blank')">
                                    Cetak Laporan
                                </button>
                            </form>

                            <div class="table-responsive mt-4">
                                <table class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Pemesanan</th>
                                            <th>Nama Pembeli</th>
                                            <th>Nama Produk</th>
                                            <th>Jumlah</th>
                                            <th>Harga Satuan</th>
                                            <th>Total Harga</th>
                                            <th>Tanggal Pemesanan</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = mysqli_fetch_assoc($result_pemesanan)): ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= htmlspecialchars($row['kode_pemesanan']); ?></td>
                                                <td><?= htmlspecialchars($row['nama_pembeli']); ?></td>
                                                <td><?= htmlspecialchars($row['nama_produk']); ?></td>
                                                <td><?= htmlspecialchars($row['jumlah']); ?></td>
                                                <td>Rp <?= number_format($row['harga_satuan'], 0, ',', '.'); ?></td>
                                                <td>Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                                <td><?= htmlspecialchars($row['tanggal_pemesanan']); ?></td>
                                                <td><?= htmlspecialchars($row['status_pemesanan']); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>
</body>

</html>