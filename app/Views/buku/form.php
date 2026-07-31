<?php $isEdit = isset($data['buku']) && $data['buku']; ?>

<div class="flex-between align-center mb-4">
    <div>
        <h1 style="font-size:1.75rem; font-weight:800;"><?= $isEdit ? 'Edit Buku' : 'Tambah Buku Baru' ?></h1>
        <p class="text-muted">Masukkan detail informasi buku.</p>
    </div>
    <a href="index.php?url=buku/index" class="btn btn-secondary">← Batal</a>
</div>

<div class="card" style="max-width: 800px;">
    <?php
    $action = $isEdit
        ? "index.php?url=buku/update/{$data['buku']['id_buku']}"
        : "index.php?url=buku/simpan";
    ?>
    <form action="<?= $action ?>" method="POST">
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">ID Buku</label>
                <input type="text" name="id_buku" class="form-control"
                       value="<?= $isEdit ? $data['buku']['id_buku'] : ($data['next_id'] ?? '') ?>"
                       <?= $isEdit ? 'readonly style="background:#F1F5F9; color:#94A3B8;"' : '' ?> required>
            </div>
            <div class="form-group">
                <label class="form-label">Kategori</label>
                <select name="id_kategori" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach($data['kategori'] as $k): ?>
                    <option value="<?= $k['id_kategori'] ?>"
                        <?= ($isEdit && $data['buku']['id_kategori'] == $k['id_kategori']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($k['nama_kategori']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label">Judul Buku</label>
            <input type="text" name="judul" class="form-control" placeholder="Masukkan judul lengkap buku..."
                   value="<?= $isEdit ? htmlspecialchars($data['buku']['judul']) : '' ?>" required>
        </div>
        
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Penulis</label>
                <input type="text" name="penulis" class="form-control" placeholder="Nama penulis..."
                       value="<?= $isEdit ? htmlspecialchars($data['buku']['penulis']) : '' ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Penerbit</label>
                <input type="text" name="penerbit" class="form-control" placeholder="Nama penerbit..."
                       value="<?= $isEdit ? htmlspecialchars($data['buku']['penerbit']) : '' ?>" required>
            </div>
        </div>
        
        <div class="form-group" style="max-width: 200px;">
            <label class="form-label">Stok Tersedia</label>
            <input type="number" name="stok" class="form-control" min="0"
                   value="<?= $isEdit ? $data['buku']['stok'] : '0' ?>" required>
        </div>
        
        <div class="mt-4" style="border-top:1px solid var(--border-light); padding-top:1.5rem;">
            <button type="submit" class="btn btn-primary btn-lg"><?= $isEdit ? 'Simpan Perubahan' : 'Simpan Buku Baru' ?></button>
        </div>
    </form>
</div>
