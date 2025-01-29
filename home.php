<?php include 'template/header.php'; ?>
<?php include 'koneksi.php'; ?>

<?php
// Query untuk menghitung jumlah data
$query_member = "SELECT COUNT(*) as total_member FROM member";
$result_member = mysqli_query($koneksi, $query_member);
$total_member = mysqli_fetch_assoc($result_member)['total_member'];

$query_produk = "SELECT COUNT(*) as total_produk FROM produk";
$result_produk = mysqli_query($koneksi, $query_produk);
$total_produk = mysqli_fetch_assoc($result_produk)['total_produk'];

$query_pemesanan = "SELECT COUNT(*) as total_pemesanan FROM pemesanan";
$result_pemesanan = mysqli_query($koneksi, $query_pemesanan);
$total_pemesanan = mysqli_fetch_assoc($result_pemesanan)['total_pemesanan'];
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
                        <!-- Widget Total Member -->
                        <div class="col-md-4">
                            <div class="card shadow">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Total Member</h5>
                                </div>
                                <div class="card-body text-center">
                                    <h3><?= $total_member; ?> Member</h3>
                                </div>
                            </div>
                        </div>

                        <!-- Widget Total Produk -->
                        <div class="col-md-4">
                            <div class="card shadow">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">Total Produk</h5>
                                </div>
                                <div class="card-body text-center">
                                    <h3><?= $total_produk; ?> Produk</h3>
                                </div>
                            </div>
                        </div>

                        <!-- Widget Total Pemesanan -->
                        <div class="col-md-4">
                            <div class="card shadow">
                                <div class="card-header bg-warning text-white">
                                    <h5 class="mb-0">Total Pemesanan</h5>
                                </div>
                                <div class="card-body text-center">
                                    <h3><?= $total_pemesanan; ?> Pemesanan</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        
        <footer class="app-footer">
        </footer>
    </div>
</body>
</html>
