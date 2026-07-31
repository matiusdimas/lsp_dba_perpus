<?php include __DIR__ . '/../layouts/_flash.php'; ?>

<div class="flex-between align-center mb-4">
    <div>
        <h1 style="font-size:1.75rem; font-weight:800;">Arsip Dokumen Pribadi</h1>
        <p class="text-muted">Kelola dokumen internal dan arsip perpustakaan.</p>
    </div>
    <?php if($_SESSION['user']['role'] !== 'Anggota'): ?>
    <a href="index.php?url=dokumen/tambah" class="btn btn-primary">+ Unggah Dokumen</a>
    <?php endif; ?>
</div>

<div class="card mb-4" style="padding: 1rem 1.5rem;">
    <form action="index.php" method="GET" class="search-form" style="display: flex; gap: 0.5rem;">
        <input type="hidden" name="url" value="dokumen/index">
        <input type="text" name="search" placeholder="🔍 Cari berdasarkan judul atau nama file..." 
               value="<?= htmlspecialchars($data['keyword'] ?? '') ?>" class="form-control" style="flex:1;">
        <button type="submit" class="btn btn-primary">Cari</button>
        <?php if(!empty($data['keyword'])): ?>
        <a href="index.php?url=dokumen/index" class="btn btn-secondary">Reset</a>
        <?php endif; ?>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul Dokumen</th>
                    <th>Nama File</th>
                    <th>Jenis</th>
                    <th>Versi</th>
                    <th>Diupload</th>
                    <?php if($_SESSION['user']['role'] !== 'Anggota'): ?>
                    <th>Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['dokumen'] as $row): ?>
                <tr>
                    <td><strong><?= $row['id_dokumen'] ?></strong></td>
                    <td style="font-weight:600; color:var(--text-main);"><?= htmlspecialchars($row['judul_dokumen']) ?></td>
                    <td><code><?= htmlspecialchars($row['nama_file']) ?></code></td>
                    <td>
                        <?php
                        $jenis = strtolower($row['jenis_file']);
                        $badgeClass = 'info';
                        if($jenis == 'pdf') $badgeClass = 'danger';
                        if($jenis == 'docx') $badgeClass = 'primary';
                        if($jenis == 'xlsx') $badgeClass = 'success';
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= strtoupper($jenis) ?></span>
                    </td>
                    <td><span class="badge warning">v<?= htmlspecialchars($row['versi']) ?></span></td>
                    <td><?= !empty($row['tgl_unggah']) ? date('d/m/Y', strtotime($row['tgl_unggah'])) : '-' ?></td>
                    <?php if($_SESSION['user']['role'] !== 'Anggota'): ?>
                    <td class="action-cell">
                        <a href="index.php?url=dokumen/edit/<?= $row['id_dokumen'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                        <a href="index.php?url=dokumen/hapus/<?= $row['id_dokumen'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus dokumen ini?')">Hapus</a>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($data['dokumen'])): ?>
                <tr><td colspan="<?= $_SESSION['user']['role'] !== 'Anggota' ? 7 : 6 ?>" class="text-center text-muted" style="padding: 3rem;">Belum ada dokumen publik.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
