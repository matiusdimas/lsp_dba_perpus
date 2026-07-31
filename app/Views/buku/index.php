<?php include __DIR__ . '/../layouts/_flash.php'; ?>

<div class="flex-between align-center mb-2">
    <div>
        <h1 style="font-size:1.75rem; font-weight:800;">Katalog Buku</h1>
        <p class="text-muted"><?= $_SESSION['user']['role'] === 'Anggota' ? 'Lihat ketersediaan koleksi buku.' : 'Kelola koleksi dan ketersediaan stok.' ?></p>
    </div>
    <?php if($_SESSION['user']['role'] !== 'Anggota'): ?>
    <div class="flex-row-gap">
        <?php if($_SESSION['user']['role'] === 'Administrator'): ?>
        <a href="index.php?url=buku/fixStok" class="btn btn-warning" onclick="return confirm('Perbaiki stok negatif?')">Fix Stok</a>
        <?php endif; ?>
        <a href="index.php?url=buku/tambah" class="btn btn-primary">+ Tambah Buku</a>
    </div>
    <?php endif; ?>
</div>

<div class="card mb-4 mt-2" style="padding: 1rem 1.5rem;">
    <form action="index.php" method="GET" class="search-form">
        <input type="hidden" name="url" value="buku/index">
        <input type="text" name="search" placeholder="🔍 Cari berdasarkan judul, penulis, atau ID buku..." 
               value="<?= htmlspecialchars($data['keyword'] ?? '') ?>" class="form-control" style="flex:1;">
        <button type="submit" class="btn btn-primary">Cari</button>
        <?php if(!empty($data['keyword'])): ?>
        <a href="index.php?url=buku/index" class="btn btn-secondary">Reset</a>
        <?php endif; ?>
    </form>
</div>

<div class="card mt-2">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Penerbit</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <?php if($_SESSION['user']['role'] !== 'Anggota'): ?>
                    <th>Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['buku'] as $row): ?>
                <tr>
                    <td><strong><?= $row['id_buku'] ?></strong></td>
                    <td style="font-weight:600; color:var(--primary);"><?= htmlspecialchars($row['judul']) ?></td>
                    <td><?= htmlspecialchars($row['penulis']) ?></td>
                    <td><?= htmlspecialchars($row['penerbit']) ?></td>
                    <td><span class="badge info"><?= htmlspecialchars($row['nama_kategori']) ?></span></td>
                    <td><span class="badge <?= $row['stok'] > 0 ? 'success' : 'danger' ?>"><?= $row['stok'] > 0 ? 'Tersedia ('.$row['stok'].')' : 'Habis' ?></span></td>
                    <?php if($_SESSION['user']['role'] !== 'Anggota'): ?>
                    <td class="action-cell">
                        <a href="index.php?url=buku/edit/<?= $row['id_buku'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                        <?php if($_SESSION['user']['role'] === 'Administrator'): ?>
                        <a href="index.php?url=buku/hapus/<?= $row['id_buku'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                        <?php endif; ?>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($data['buku'])): ?>
                <tr><td colspan="<?= $_SESSION['user']['role'] !== 'Anggota' ? 7 : 6 ?>" class="text-center text-muted" style="padding: 3rem;">Tidak ada data buku.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
