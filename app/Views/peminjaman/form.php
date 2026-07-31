<div class="flex-between align-center mb-4">
    <div>
        <h1 style="font-size:1.75rem; font-weight:800;">Catat Peminjaman Baru</h1>
        <p class="text-muted">Proses transaksi peminjaman buku oleh anggota.</p>
    </div>
    <a href="index.php?url=peminjaman/index" class="btn btn-secondary">← Batal</a>
</div>

<div class="card" style="max-width: 800px;">
    <form action="index.php?url=peminjaman/simpan" method="POST">
        <div class="form-group" style="max-width: 250px;">
            <label class="form-label">ID Peminjaman</label>
            <input type="text" name="id_peminjaman" class="form-control" value="<?= $data['next_id'] ?>" readonly style="background:#F1F5F9; color:#94A3B8;">
        </div>
        
        <div class="form-group">
            <label class="form-label">Anggota Peminjam</label>
            <select name="id_anggota" class="form-control" required style="cursor:pointer;">
                <option value="">-- Pilih Anggota --</option>
                <?php foreach($data['anggota'] as $a): ?>
                <option value="<?= $a['id_anggota'] ?>"><?= $a['id_anggota'] ?> — <?= htmlspecialchars($a['nama']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Buku yang Dipinjam</label>
            <select name="id_buku" class="form-control" required style="cursor:pointer;">
                <option value="">-- Pilih Buku --</option>
                <?php foreach($data['buku'] as $b): ?>
                <option value="<?= $b['id_buku'] ?>"><?= $b['id_buku'] ?> — <?= htmlspecialchars($b['judul']) ?> (Stok: <?= $b['stok'] ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Tanggal Pinjam</label>
                <input type="date" name="tgl_pinjam" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Jatuh Tempo (7 Hari Kedepan)</label>
                <input type="date" name="tgl_jatuh_tempo" class="form-control" value="<?= date('Y-m-d', strtotime('+7 days')) ?>" required>
            </div>
        </div>
        
        <div class="mt-4" style="border-top:1px solid var(--border-light); padding-top:1.5rem;">
            <button type="submit" class="btn btn-primary btn-lg">Proses Peminjaman</button>
        </div>
    </form>
</div>
