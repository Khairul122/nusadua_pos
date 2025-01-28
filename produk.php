<?php include 'template/header.php'; ?>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include 'template/navbar.php'; ?>
        <?php include 'template/sidebar.php'; ?>
        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Produk</h3>
                        </div>
                        <div class="col-sm-6">
                        </div>
                    </div>
                </div>
            </div>
            <div class="app-content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahProdukModal">
                                Tambah Data
                            </button>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Produk</th>
                                        <th>Merek</th>
                                        <th>Harga Satuan</th>
                                        <th>Stok</th>
                                        <th>Satuan</th>
                                        <th>Deskripsi</th>
                                        <th>Gambar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include 'koneksi.php';
                                    $query = "SELECT * FROM produk";
                                    $result = mysqli_query($koneksi, $query);

                                    if (mysqli_num_rows($result) > 0) {
                                        $no = 1;
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>";
                                            echo "<td>" . $no++ . "</td>";
                                            echo "<td>" . htmlspecialchars($row['nama_produk']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['merek']) . "</td>";
                                            echo "<td>Rp " . number_format($row['harga_satuan'], 2, ',', '.') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['stok']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['satuan']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['deskripsi']) . "</td>";
                                            echo "<td>";
                                            if (!empty($row['gambar'])) {
                                                echo "<img src='image/foto_produk/" . htmlspecialchars($row['gambar']) . "' alt='Gambar Produk' width='35'>";
                                            } else {
                                                echo "Tidak ada gambar";
                                            }
                                            echo "</td>";
                                            echo "<td>
                                            <button type='button' class='btn btn-warning btn-sm' onclick='openEditModal(
                                                \"" . $row['id_produk'] . "\",
                                                \"" . htmlspecialchars($row['nama_produk']) . "\",
                                                \"" . htmlspecialchars($row['merek']) . "\",
                                                \"" . $row['harga_satuan'] . "\",
                                                \"" . $row['stok'] . "\",
                                                \"" . htmlspecialchars($row['satuan']) . "\",
                                                \"" . htmlspecialchars($row['gambar']) . "\",
                                                \"" . htmlspecialchars($row['deskripsi']) . "\"
                                            )'>Edit</button>
                                            <a href='delete-produk.php?id=" . $row['id_produk'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Apakah Anda yakin ingin menghapus produk ini?\")'>Hapus</a>
                                          </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='8' class='text-center'>Data belum tersedia</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!-- Footer -->
        <footer class="app-footer">
        </footer>

        <!-- Modal Tambah Produk -->
        <div class="modal fade" id="tambahProdukModal" tabindex="-1" aria-labelledby="tambahProdukModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="tambah-produk.php" method="post" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="tambahProdukModalLabel">Tambah Produk</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nama_produk" class="form-label">Nama Produk</label>
                                <input type="text" class="form-control" id="nama_produk" name="nama_produk" required>
                            </div>
                            <div class="mb-3">
                                <label for="merek" class="form-label">Merek</label>
                                <input type="text" class="form-control" id="merek" name="merek" required>
                            </div>
                            <div class="mb-3">
                                <label for="harga_satuan" class="form-label">Harga Satuan</label>
                                <input type="number" class="form-control" id="harga_satuan" name="harga_satuan" required>
                            </div>
                            <div class="mb-3">
                                <label for="stok" class="form-label">Stok</label>
                                <input type="number" class="form-control" id="stok" name="stok" required>
                            </div>
                            <div class="mb-3">
                                <label for="satuan" class="form-label">Satuan</label>
                                <input type="text" class="form-control" id="satuan" name="satuan" required>
                            </div>
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="gambar" class="form-label">Gambar Produk</label>
                                <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit Produk -->
        <div class="modal fade" id="editProdukModal" tabindex="-1" aria-labelledby="editProdukModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="editProdukForm" action="edit-produk.php" method="post" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProdukModalLabel">Edit Produk</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="edit_id_produk" name="id_produk">
                            <div class="mb-3">
                                <label for="edit_nama_produk" class="form-label">Nama Produk</label>
                                <input type="text" class="form-control" id="edit_nama_produk" name="nama_produk" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_merek" class="form-label">Merek</label>
                                <input type="text" class="form-control" id="edit_merek" name="merek" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_harga_satuan" class="form-label">Harga Satuan</label>
                                <input type="number" class="form-control" id="edit_harga_satuan" name="harga_satuan" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_stok" class="form-label">Stok</label>
                                <input type="number" class="form-control" id="edit_stok" name="stok" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_satuan" class="form-label">Satuan</label>
                                <input type="text" class="form-control" id="edit_satuan" name="satuan" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="edit_gambar" class="form-label">Gambar Produk</label>
                                <input type="file" class="form-control" id="edit_gambar" name="gambar" accept="image/*">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(id, nama, merek, harga, stok, satuan, gambar, deskripsi) {
            document.getElementById('edit_id_produk').value = id;
            document.getElementById('edit_nama_produk').value = nama;
            document.getElementById('edit_merek').value = merek;
            document.getElementById('edit_harga_satuan').value = harga;
            document.getElementById('edit_stok').value = stok;
            document.getElementById('edit_satuan').value = satuan;
            document.getElementById('edit_deskripsi').value = deskripsi;

            let oldPreview = document.getElementById('imagePreview');
            if (oldPreview) {
                oldPreview.remove();
            }

            if (gambar) {
                const imagePreview = `<img id="imagePreview" src="image/foto_produk/${gambar}" alt="Preview Gambar" width="100" class="mt-2">`;
                document.getElementById('edit_gambar').insertAdjacentHTML('afterend', imagePreview);
            }

            const modal = new bootstrap.Modal(document.getElementById('editProdukModal'));
            modal.show();
        }
    </script>


</body>

</html>