<?php
include 'koneksi.php';
include 'template/header.php';

$query = "SELECT DISTINCT p.kode_pemesanan, p.tanggal_pemesanan, p.status_pemesanan, m.nama AS nama_pembeli
          FROM pemesanan p
          JOIN member m ON p.id_member = m.id_member
          ORDER BY p.tanggal_pemesanan DESC";

$result = mysqli_query($koneksi, $query);
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
                            <h3 class="mb-0">Kasir</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="app-content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4>Data Pemesanan</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered" id="tabelPemesanan">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Pemesanan</th>
                                        <th>Nama Pembeli</th>
                                        <th>Tanggal Pemesanan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (mysqli_num_rows($result) > 0):
                                        $no = 1;
                                        while ($row = mysqli_fetch_assoc($result)):
                                    ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= htmlspecialchars($row['kode_pemesanan']); ?></td>
                                                <td><?= htmlspecialchars($row['nama_pembeli']); ?></td>
                                                <td><?= htmlspecialchars($row['tanggal_pemesanan']); ?></td>
                                                <td>
                                                    <span class="badge bg-primary"><?= htmlspecialchars($row['status_pemesanan']); ?></span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-info btn-sm" onclick="lihatDetail('<?= $row['kode_pemesanan']; ?>')">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <button class="btn btn-warning btn-sm" onclick="editStatus('<?= $row['kode_pemesanan']; ?>')">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-sm" onclick="hapusPesanan('<?= $row['kode_pemesanan']; ?>')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                    <a href="c-faktur.php?kode_pemesanan=<?= $row['kode_pemesanan']; ?>" class="btn btn-primary btn-sm" target="_blank">
                                                        <i class="bi bi-printer"></i>
                                                    </a>
                                                </td>

                                            </tr>
                                        <?php
                                        endwhile;
                                    else:
                                        ?>
                                        <tr>
                                            <td colspan="6" class="text-center">Belum ada pesanan.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer class="app-footer">
        </footer>
    </div>

    <!-- Modal Detail Pesanan -->
    <div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalDetailContent">
                    <!-- Content will be loaded dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Status -->
    <div class="modal fade" id="modalEditStatus" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Status Pemesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formEditStatus" action="edit-status.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="kode_pemesanan" id="editKodePemesanan">
                        <div class="form-group">
                            <label for="status">Pilih Status</label>
                            <select class="form-control" name="status" id="editStatus">
                                <option value="Menunggu Konfirmasi">Menunggu Konfirmasi</option>
                                <option value="Selesai">Selesai</option>
                                <option value="Batal">Batal</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function lihatDetail(kodePemesanan) {
            $.ajax({
                url: 'get-detail-pesanan.php',
                type: 'POST',
                data: {
                    kode_pemesanan: kodePemesanan
                },
                success: function(response) {
                    $('#modalDetailContent').html(response);
                    $('#modalDetail').modal('show');
                }
            });
        }

        function editStatus(kodePemesanan) {
            $('#editKodePemesanan').val(kodePemesanan);
            $('#modalEditStatus').modal('show');
        }

        function hapusPesanan(kodePemesanan) {
            if (confirm('Apakah Anda yakin ingin menghapus pesanan ini?')) {
                window.location.href = 'hapus-pesanan.php?kode=' + kodePemesanan;
            }
        }
    </script>
</body>

</html>