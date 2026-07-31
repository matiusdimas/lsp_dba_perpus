<?php include __DIR__ . '/../layouts/_flash.php'; ?>

<div class="flex-between align-center mb-4">
    <div>
        <h1 style="font-size:1.75rem; font-weight:800;">SQL Query Explorer</h1>
        <p class="text-muted">Demonstrasi interaktif eksekusi query SQL pada database perpustakaan.</p>
    </div>
</div>

<div class="grid-2 mb-4">
    <div class="card" style="padding:1rem;">
        <h2 style="padding: 0.5rem 1rem;">Pilih Query Template</h2>
        <ul class="query-list mt-1">
            <?php
            $labels = [
                'buku_tersedia'    => '📚 Buku Tersedia (Stok > 0)',
                'peminjaman_aktif' => '📋 Laporan Peminjaman Aktif',
                'semua_anggota'    => '👥 Semua Data Anggota',
                'semua_buku'       => '📖 Semua Data Buku + Kategori',
                'pengembalian'     => '↩️ Riwayat Pengembalian & Denda',
                'data_ganda'       => '⚠️ Email Ganda',
                'stok_tidak_valid' => '🔧 Stok Tidak Valid (Negatif)',
                'struktur_relasi'  => '🔗 Struktur Relasi Antar Tabel',
            ];
            foreach($data['queries'] as $key => $sql): ?>
            <li>
                <a href="index.php?url=sql/index&query=<?= $key ?>"
                   class="<?= $data['query_key'] === $key ? 'active' : '' ?>">
                    <?= $labels[$key] ?? $key ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="card" style="padding:1rem; display:flex; flex-direction:column;">
        <h2 style="padding: 0.5rem 1rem;">SQL Statement</h2>
        <div style="flex:1; padding: 0 1rem 1rem 1rem;">
            <?php if($data['sql']): ?>
            <pre class="sql-code" style="height:100%; margin:0;"><?= htmlspecialchars($data['sql']) ?></pre>
            <?php else: ?>
            <div style="background:var(--bg-body); border-radius:var(--radius-md); border:1px dashed var(--text-light); height:100%; display:flex; align-items:center; justify-content:center;">
                <p class="text-muted">Pilih query dari panel kiri.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if(!empty($data['results'])): ?>
<div class="card">
    <h2 style="padding-bottom:1rem; border-bottom:1px solid var(--border-light); margin-bottom:0;">Hasil Query <span class="badge info ml-2"><?= count($data['results']) ?> baris</span></h2>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <?php foreach($data['columns'] as $col): ?>
                    <th><?= htmlspecialchars($col) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['results'] as $row): ?>
                <tr>
                    <?php foreach($row as $val): ?>
                    <td><?= htmlspecialchars((string)($val ?? 'NULL')) ?></td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php elseif($data['query_key']): ?>
<div class="card" style="padding:3rem;">
    <p class="text-center text-muted" style="font-size:1.125rem;">Query dieksekusi, namun tidak menghasilkan data (0 rows).</p>
</div>
<?php endif; ?>
