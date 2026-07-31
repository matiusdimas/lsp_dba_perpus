<?php include __DIR__ . '/../layouts/_flash.php'; ?>

<div class="flex-between align-center mb-4">
    <div>
        <h1 style="font-size:1.75rem; font-weight:800;"><?= $_SESSION['user']['role'] === 'Anggota' ? 'Riwayat Pengembalian Saya' : 'Data Pengembalian & Denda' ?></h1>
        <p class="text-muted">Riwayat transaksi penyelesaian peminjaman buku.</p>
    </div>
</div>

<div class="card mb-4" style="padding: 1rem 1.5rem;">
    <form action="index.php" method="GET" class="search-form" style="display: flex; gap: 0.5rem;">
        <input type="hidden" name="url" value="pengembalian/index">
        <input type="text" name="search" placeholder="🔍 Cari ID, judul buku, atau nama peminjam..." 
               value="<?= htmlspecialchars($data['keyword'] ?? '') ?>" class="form-control" style="flex:1;">
        <button type="submit" class="btn btn-primary">Cari</button>
        <?php if(!empty($data['keyword'])): ?>
        <a href="index.php?url=pengembalian/index" class="btn btn-secondary">Reset</a>
        <?php endif; ?>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID Kembali</th>
                    <th>ID Pinjam</th>
                    <th>Peminjam</th>
                    <th>Tgl Dikembalikan</th>
                    <th>Denda</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['pengembalian'] as $row): ?>
                <tr>
                    <td><strong><?= $row['id_pengembalian'] ?></strong></td>
                    <td><span class="text-muted"><?= $row['id_peminjaman'] ?></span></td>
                    <td style="font-weight:600;"><?= htmlspecialchars($row['nama_peminjam']) ?></td>
                    <td><?= $row['tgl_kembali'] ?></td>
                    <td>
                        <?php if($row['denda'] > 0): ?>
                        <span class="badge danger" style="font-size:0.875rem;">Rp <?= number_format($row['denda'], 0, ',', '.') ?></span>
                        <?php else: ?>
                        <span class="badge success">Tepat Waktu (Rp 0)</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($data['pengembalian'])): ?>
                <tr><td colspan="5" class="text-center text-muted" style="padding: 3rem;">Belum ada riwayat pengembalian.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
