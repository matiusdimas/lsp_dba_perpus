<?php include __DIR__ . '/../layouts/_flash.php'; ?>

<div class="flex-between align-center mb-4">
    <div>
        <h1 style="font-size:1.75rem; font-weight:800;">Data Anggota</h1>
        <p class="text-muted">Kelola data keanggotaan dan profil pengguna perpustakaan.</p>
    </div>
    <div class="flex-row-gap">
        <a href="index.php?url=anggota/tambah" class="btn btn-primary">+ Tambah Anggota</a>
    </div>
</div>

<div class="card mb-4 mt-2" style="padding: 1rem 1.5rem;">
    <form action="index.php" method="GET" class="search-form">
        <input type="hidden" name="url" value="anggota/index">
        <input type="text" name="search" placeholder="🔍 Cari berdasarkan nama atau ID anggota..." 
               value="<?= htmlspecialchars($data['keyword'] ?? '') ?>" class="form-control" style="flex:1;">
        <button type="submit" class="btn btn-primary">Cari</button>
        <?php if(!empty($data['keyword'])): ?>
        <a href="index.php?url=anggota/index" class="btn btn-secondary">Reset</a>
        <?php endif; ?>
    </form>
</div>

<div class="grid-2 mb-4 mt-2">
    <div class="card">
        <h2>Import Data CSV</h2>
        <div class="flex-between align-center" style="margin-bottom:1rem;">
            <p class="text-muted" style="font-size:0.875rem; margin:0;">Format: <code>nama, email, no_hp, alamat</code></p>
            <a href="sample_anggota.csv" download class="btn btn-secondary btn-sm" style="border-radius: var(--radius-sm);">Unduh Template CSV</a>
        </div>
        <form action="index.php?url=anggota/import" method="POST" enctype="multipart/form-data" class="search-form">
            <input type="file" name="file_csv" accept=".csv" required class="form-control" style="border-radius: var(--radius-sm); flex:1;">
            <button type="submit" class="btn btn-primary" style="border-radius: var(--radius-sm);">Import</button>
        </form>
    </div>
    
    <div class="card">
        <h2>Kualitas Data</h2>
        <ul class="quality-list">
            <li>
                <div class="flex-between">
                    <span>Email ganda: <strong><?= count($data['ganda']) ?> kasus</strong></span>
                    <?php if(count($data['ganda']) > 0): ?>
                        <a href="index.php?url=anggota/clean" class="badge danger" style="text-decoration:none; cursor:pointer;" onclick="return confirm('Hapus semua duplikat?')">Bersihkan</a>
                    <?php else: ?>
                        <span class="badge success">✓ Bersih</span>
                    <?php endif; ?>
                </div>
                <?php if(!empty($data['ganda'])): ?>
                <div class="mt-1" style="font-size:0.75rem;">
                    <?php foreach($data['ganda'] as $g): ?>
                        <span class="text-muted" style="margin-right: 8px;"><?= $g['email'] ?> (×<?= $g['jumlah'] ?>)</span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </li>
            <li>
                <div class="flex-between">
                    <span>Data tidak valid: <strong><?= count($data['invalid']) ?> baris</strong></span>
                    <span class="badge <?= count($data['invalid']) > 0 ? 'warning' : 'success' ?>">
                        <?= count($data['invalid']) > 0 ? '⚠ Perlu Diperbaiki' : '✓ Valid' ?>
                    </span>
                </div>
            </li>
        </ul>
    </div>
</div>

<div class="card mt-2">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No HP</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['anggota'] as $row): ?>
                <tr>
                    <td><strong><?= $row['id_anggota'] ?></strong></td>
                    <td style="font-weight:600;"><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['no_hp'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($row['alamat'] ?? '-') ?></td>
                    <td class="action-cell">
                        <a href="index.php?url=anggota/edit/<?= $row['id_anggota'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                        <a href="index.php?url=anggota/hapus/<?= $row['id_anggota'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus anggota ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($data['anggota'])): ?>
                <tr><td colspan="6" class="text-center text-muted" style="padding: 3rem;">Belum ada data anggota.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
