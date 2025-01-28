<?php
include 'template/header.php';
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id_member = mysqli_real_escape_string($koneksi, $_GET['id']);

    $query_check = "SELECT * FROM member WHERE id_member = '$id_member'";
    $result_check = mysqli_query($koneksi, $query_check);

    if (mysqli_num_rows($result_check) > 0) {
        $query_delete = "DELETE FROM member WHERE id_member = '$id_member'";
        if (mysqli_query($koneksi, $query_delete)) {
            echo "<script>alert('Member berhasil dihapus!'); window.location.href = 'member.php';</script>";
            exit;
        } else {
            echo "<script>alert('Gagal menghapus member!'); window.location.href = 'member.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('Member tidak ditemukan!'); window.location.href = 'member.php';</script>";
        exit;
    }
}
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
                            <h3 class="mb-0">Member</h3>
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
                            <h5 class="card-title mb-0">Data Member</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Username</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM member";
                                    $result = mysqli_query($koneksi, $query);

                                    if (mysqli_num_rows($result) > 0) {
                                        $no = 1;
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>";
                                            echo "<td>" . $no++ . "</td>";
                                            echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                                            echo "<td>
                                                    <a href='dashboard.php?id=" . $row['id_member'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Apakah Anda yakin ingin menghapus member ini?\")'>Hapus</a>
                                                  </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='4' class='text-center'>Data belum tersedia</td></tr>";
                                    }
                                    ?>
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
</body>
</html>
