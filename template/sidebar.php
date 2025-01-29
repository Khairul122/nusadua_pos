<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="./index.html" class="brand-link">
            <span class="brand-text fw-bold">Nusa Dua</span>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <?php
                if (isset($_SESSION['level'])) { 
                    if ($_SESSION['level'] === "Kasir") {
                        echo '<li class="nav-item">
                                <a href="home.php" class="nav-link">
                                    <p>Home</p>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="kasir.php" class="nav-link">
                                    <p>Kasir</p>
                                </a>
                              </li>';
                    } else if ($_SESSION['level'] === "Admin") {
                        echo '<li class="nav-item">
                                <a href="home.php" class="nav-link">
                                    <p>Halaman Utama</p>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="produk.php" class="nav-link">
                                    <p>Produk</p>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="member.php" class="nav-link">
                                    <p>Member</p>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="kasir.php" class="nav-link">
                                    <p>Kasir</p>
                                </a>
                              </li>';
                    } else if ($_SESSION['level'] === "Pimpinan") {
                        echo '<li class="nav-item">
                                <a href="home.php" class="nav-link">
                                    <p>Home</p>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="laporan.php" class="nav-link">
                                    <p>Laporan</p>
                                </a>
                              </li>';
                    } else {
                        echo '<li class="nav-item">
                                <a href="#" class="nav-link">
                                    <p>Role tidak dikenal</p>
                                </a>
                              </li>';
                    }
                }
                ?>
            </ul>
        </nav>
    </div>
</aside>
