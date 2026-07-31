<?php include __DIR__ . '/../layouts/_flash.php'; ?>

<div class="flex-between align-center mb-4">
    <div>
        <h1 style="font-size:1.75rem; font-weight:800;"><?= $_SESSION['user']['role'] === 'Anggota' ? 'Peminjaman Saya' : 'Data Peminjaman Aktif' ?></h1>
        <p class="text-muted">Pantau sirkulasi peminjaman buku dan status jatuh tempo.</p>
    </div>
    <div class="flex-row-gap">
        <?php if($_SESSION['user']['role'] !== 'Anggota'): ?>
        <a href="index.php?url=peminjaman/tambah" class="btn btn-primary">+ Catat Peminjaman</a>
        <?php endif; ?>
    </div>
</div>

<div class="card mb-4" style="padding: 1rem 1.5rem;">
    <form action="index.php" method="GET" class="search-form" style="display:flex; gap:1rem; flex-wrap:wrap;">
        <input type="hidden" name="url" value="peminjaman/index">
        <select name="status" class="form-control" onchange="this.form.submit()" style="border-radius:99px; cursor:pointer; width:auto;">
            <option value="">Semua Status</option>
            <option value="Dipinjam" <?= ($data['status_filter'] ?? '') === 'Dipinjam' ? 'selected' : '' ?>>Dipinjam</option>
            <option value="Selesai" <?= ($data['status_filter'] ?? '') === 'Selesai' ? 'selected' : '' ?>>Selesai</option>
        </select>
        <input type="text" name="search" placeholder="🔍 Cari ID, buku, atau peminjam..." 
               value="<?= htmlspecialchars($data['keyword'] ?? '') ?>" class="form-control" style="flex:1;">
        <button type="submit" class="btn btn-primary">Cari</button>
        <?php if(!empty($data['keyword']) || !empty($data['status_filter'])): ?>
        <a href="index.php?url=peminjaman/index" class="btn btn-secondary">Reset</a>
        <?php endif; ?>
    </form>
</div>

<div class="card">
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
                <?php foreach($data['peminjaman'] as $row): ?>
                <?php
                    $jatuhTempo = new DateTime($row['tgl_jatuh_tempo']);
                    $today      = new DateTime();
                    $terlambat  = $today > $jatuhTempo && $row['status'] !== 'Selesai';
                ?>
                <tr>
                    <td><strong><?= $row['id_peminjaman'] ?></strong></td>
                    <td style="font-weight:600; color:var(--primary);"><?= htmlspecialchars($row['judul_buku']) ?></td>
                    <td><?= htmlspecialchars($row['nama_peminjam']) ?></td>
                    <td><?= $row['tgl_pinjam'] ?></td>
                    <td>
                        <span class="badge <?= $terlambat ? 'danger' : 'warning' ?>">
                            <?= $terlambat ? 'Terlambat (' . $row['tgl_jatuh_tempo'] . ')' : $row['tgl_jatuh_tempo'] ?>
                        </span>
                    </td>
                    <td>
                        <?php if($row['status'] === 'Selesai'): ?>
                            <span class="badge success">Selesai</span>
                        <?php else: ?>
                            <span class="badge info">Dipinjam</span>
                        <?php endif; ?>
                    </td>
                    <?php if($_SESSION['user']['role'] !== 'Anggota'): ?>
                    <td class="action-cell">
                        <?php if($row['status'] !== 'Selesai'): ?>
                        <a href="index.php?url=pengembalian/tambah/<?= $row['id_peminjaman'] ?>" class="btn btn-sm btn-success">Selesaikan (Kembalikan)</a>
                        <?php else: ?>
                        <span class="text-muted" style="font-size:0.875rem;">✔ Dikembalikan</span>
                        <?php endif; ?>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($data['peminjaman'])): ?>
                <tr><td colspan="<?= $_SESSION['user']['role'] !== 'Anggota' ? 7 : 6 ?>" class="text-center text-muted" style="padding: 3rem;">Tidak ada data transaksi.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
