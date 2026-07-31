<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Informasi Perpustakaan Kampus">
    <title><?= htmlspecialchars($data['title'] ?? 'Sistem Perpustakaan') ?></title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Font Awesome (for minimalist icons if needed, or we just use emojis for simplicity) -->
</head>
<body>
    <div class="app-wrapper">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="index.php" class="sidebar-brand">Perpus<span>Kampus</span></a>
            </div>
            
            <nav class="sidebar-menu">
                <a href="index.php" class="menu-item <?= (isset($_GET['url']) && $_GET['url'] === 'home/index' || !isset($_GET['url'])) ? 'active' : '' ?>">
                    <span>📊</span> Dashboard
                </a>
                
                <div class="menu-category">Katalog Utama</div>
                <a href="index.php?url=buku/index" class="menu-item <?= (strpos($_GET['url'] ?? '', 'buku/') === 0) ? 'active' : '' ?>">
                    <span>📚</span> Data Buku
                </a>
                <?php if(isset($_SESSION['user']) && $_SESSION['user']['role'] !== 'Anggota'): ?>
                <a href="index.php?url=anggota/index" class="menu-item <?= (strpos($_GET['url'] ?? '', 'anggota/') === 0) ? 'active' : '' ?>">
                    <span>👥</span> Data Anggota
                </a>
                <?php endif; ?>

                <?php if(isset($_SESSION['user'])): ?>
                <div class="menu-category">Transaksi</div>
                <a href="index.php?url=peminjaman/index" class="menu-item <?= (strpos($_GET['url'] ?? '', 'peminjaman/') === 0) ? 'active' : '' ?>">
                    <span>📋</span> <?= $_SESSION['user']['role'] === 'Anggota' ? 'Peminjaman Saya' : 'Peminjaman' ?>
                </a>
                <a href="index.php?url=pengembalian/index" class="menu-item <?= (strpos($_GET['url'] ?? '', 'pengembalian/') === 0) ? 'active' : '' ?>">
                    <span>↩️</span> <?= $_SESSION['user']['role'] === 'Anggota' ? 'Pengembalian Saya' : 'Pengembalian' ?>
                </a>
                <?php endif; ?>

                <div class="menu-category">Informasi</div>
                <a href="index.php?url=dokumen/index" class="menu-item <?= (strpos($_GET['url'] ?? '', 'dokumen/') === 0) ? 'active' : '' ?>">
                    <span>🗂️</span> Dokumen Publik
                </a>

                <?php if(isset($_SESSION['user']) && $_SESSION['user']['role'] === 'Administrator'): ?>
                <div class="menu-category">Sistem & Keamanan</div>
                <a href="index.php?url=user/index" class="menu-item <?= (strpos($_GET['url'] ?? '', 'user/') === 0) ? 'active' : '' ?>">
                    <span>🔑</span> Manajemen Akun
                </a>
                <a href="index.php?url=hakAkses/index" class="menu-item <?= (strpos($_GET['url'] ?? '', 'hakAkses/') === 0) ? 'active' : '' ?>">
                    <span>🔐</span> Hak Akses (DCL)
                </a>
                <a href="index.php?url=sql/index" class="menu-item <?= (strpos($_GET['url'] ?? '', 'sql/') === 0) ? 'active' : '' ?>">
                    <span>🔍</span> SQL Explorer
                </a>
                <?php endif; ?>
            </nav>

            <?php if(isset($_SESSION['user'])): ?>
            <div class="sidebar-footer">
                <div class="user-profile">
                    <div class="user-avatar"><?= strtoupper(substr($_SESSION['user']['nama_lengkap'], 0, 1)) ?></div>
                    <div class="user-info">
                        <span class="user-name"><?= htmlspecialchars($_SESSION['user']['nama_lengkap']) ?></span>
                        <span class="user-role"><?= $_SESSION['user']['role'] ?></span>
                    </div>
                </div>
                <a href="index.php?url=auth/logout" class="logout-btn">Log Out</a>
            </div>
            <?php else: ?>
            <div class="sidebar-footer">
                <a href="index.php?url=auth/login" class="btn btn-primary" style="width:100%;">Login</a>
            </div>
            <?php endif; ?>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <header class="topbar">
                <div class="page-title">
                    <?= htmlspecialchars($data['title'] ?? 'Sistem Perpustakaan') ?>
                </div>
                <!-- Future topbar items can go here (notifications, search, etc.) -->
            </header>
            
            <div class="content-wrapper">
