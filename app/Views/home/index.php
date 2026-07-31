<?php include __DIR__ . '/../layouts/_flash.php'; ?>

<div class="flex-between align-center mb-2">
    <div>
        <h1 style="font-size:1.75rem; font-weight:800;">Overview</h1>
        <p class="text-muted">Ringkasan statistik perpustakaan.</p>
    </div>
</div>

<div class="stats-grid">
    <a href="index.php?url=buku/index" class="stat-card">
        <div class="stat-icon">📚</div>
        <div class="stat-details">
            <h3>Total Buku</h3>
            <p><?= $data['total_buku'] ?></p>
        </div>
    </a>
    <?php if($_SESSION['user']['role'] !== 'Anggota'): ?>
    <a href="index.php?url=anggota/index" class="stat-card">
        <div class="stat-icon" style="background:#E0E7FF;">👥</div>
        <div class="stat-details">
            <h3>Total Anggota</h3>
            <p><?= $data['total_anggota'] ?></p>
        </div>
    </a>
    <?php endif; ?>
    <a href="index.php?url=peminjaman/index" class="stat-card">
        <div class="stat-icon" style="background:#FEF3C7; color:#B45309;">📋</div>
        <div class="stat-details">
            <h3>Peminjaman Aktif</h3>
            <p><?= count($data['peminjaman_aktif']) ?></p>
        </div>
    </a>
    <a href="index.php?url=pengembalian/index" class="stat-card">
        <div class="stat-icon" style="background:#D1FAE5; color:#065F46;">↩️</div>
        <div class="stat-details">
            <h3>Pengembalian</h3>
            <p><?= $data['total_pengembalian'] ?></p>
        </div>
    </a>
</div>

<div class="card mb-4" style="padding: 2rem; text-align: center; background: linear-gradient(135deg, var(--primary) 0%, #1e40af 100%); color: white; border: none; border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);">
    <h2 style="font-size: 1.75rem; margin-bottom: 1rem; color: white;">Cari Koleksi Buku Kami</h2>
    <p style="margin-bottom: 1.5rem; opacity: 0.9;">Temukan buku, jurnal, dan referensi yang Anda butuhkan dengan cepat.</p>
    <form action="index.php" method="GET" style="display: flex; max-width: 600px; margin: 0 auto; gap: 0.5rem; align-items: stretch;">
        <input type="hidden" name="url" value="buku/index">
        <input type="text" name="search" placeholder="🔍 Masukkan judul buku, penulis, atau topik..." class="form-control" style="flex: 1; padding: 1rem 1.5rem; border-radius: 99px; border: none; font-size: 1rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
        <button type="submit" class="btn" style="background: white; color: var(--primary); border-radius: 99px; padding: 0 2rem; font-weight: 600;">Cari</button>
    </form>
</div>

<div class="card mb-4">
    <div class="flex-between align-center" style="margin-bottom:1.5rem;">
        <h2 style="margin:0; border:none; padding:0;"><?= $_SESSION['user']['role'] === 'Anggota' ? 'Peminjaman Saya' : 'Peminjaman Aktif' ?></h2>
        <?php if($_SESSION['user']['role'] !== 'Anggota'): ?>
            <a href="index.php?url=peminjaman/tambah" class="btn btn-primary btn-sm">+ Catat Peminjaman</a>
        <?php endif; ?>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Buku</th>
                    <th>Peminjam</th>
                    <th>Tgl Pinjam</th>
                    <th>Jatuh Tempo</th>
                    <th>Status</th>
                    <?php if($_SESSION['user']['role'] !== 'Anggota'): ?>
                    <th>Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['peminjaman_aktif'] as $row): ?>
                <?php
                    $jatuhTempo = new DateTime($row['tgl_jatuh_tempo']);
                    $today      = new DateTime();
                    $terlambat  = $today > $jatuhTempo;
                ?>
                <tr>
                    <td><strong><?= $row['id_peminjaman'] ?></strong></td>
                    <td><?= htmlspecialchars($row['judul_buku']) ?></td>
                    <td><?= htmlspecialchars($row['nama_peminjam']) ?></td>
                    <td><?= $row['tgl_pinjam'] ?></td>
                    <td><span class="badge <?= $terlambat ? 'danger' : 'warning' ?>"><?= $row['tgl_jatuh_tempo'] ?></span></td>
                    <td><span class="badge info"><?= $row['status'] ?></span></td>
                    <?php if($_SESSION['user']['role'] !== 'Anggota'): ?>
                    <td><a href="index.php?url=pengembalian/tambah/<?= $row['id_peminjaman'] ?>" class="btn btn-sm btn-success">Kembalikan</a></td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($data['peminjaman_aktif'])): ?>
                <tr><td colspan="<?= $_SESSION['user']['role'] !== 'Anggota' ? 7 : 6 ?>" class="text-center text-muted">Tidak ada peminjaman aktif.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
