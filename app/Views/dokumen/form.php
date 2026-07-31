<?php $isEdit = isset($data['dokumen']) && $data['dokumen']; ?>

<div class="flex-between align-center mb-4">
    <div>
        <h1 style="font-size:1.75rem; font-weight:800;"><?= $isEdit ? 'Edit Dokumen' : 'Tambah Dokumen Baru' ?></h1>
        <p class="text-muted">Kelola arsip dan berkas elektronik perpustakaan.</p>
    </div>
    <a href="index.php?url=dokumen/index" class="btn btn-secondary">← Batal</a>
</div>

<div class="card" style="max-width: 800px;">
    <?php
    $action = $isEdit
        ? "index.php?url=dokumen/update/{$data['dokumen']['id_dokumen']}"
        : "index.php?url=dokumen/simpan";
    ?>
    <form action="<?= $action ?>" method="POST">
        <div class="form-group">
            <label class="form-label">Judul Dokumen</label>
            <input type="text" name="judul_dokumen" class="form-control" placeholder="Contoh: Panduan Peminjaman Buku"
                   value="<?= $isEdit ? htmlspecialchars($data['dokumen']['judul_dokumen']) : '' ?>" required>
        </div>
        
        <div class="form-group">
            <label class="form-label">Nama File</label>
            <input type="text" name="nama_file" class="form-control" placeholder="contoh: panduan.pdf"
                   value="<?= $isEdit ? htmlspecialchars($data['dokumen']['nama_file']) : '' ?>" required>
        </div>
        
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Jenis File</label>
                <select name="jenis_file" class="form-control" required style="cursor:pointer;">
                    <?php foreach(['pdf', 'docx', 'xlsx', 'txt', 'jpg', 'png'] as $jenis): ?>
                    <option value="<?= $jenis ?>" <?= ($isEdit && $data['dokumen']['jenis_file'] === $jenis) ? 'selected' : '' ?>>
                        <?= strtoupper($jenis) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Versi Dokumen</label>
                <input type="text" name="versi" class="form-control" placeholder="1.0"
                       value="<?= $isEdit ? htmlspecialchars($data['dokumen']['versi']) : '1.0' ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label">Lokasi File (Path Fisik / URL)</label>
            <input type="text" name="lokasi_file" class="form-control" placeholder="/uploads/docs/..."
                   value="<?= $isEdit ? htmlspecialchars($data['dokumen']['lokasi_file']) : '' ?>" required>
        </div>
        
        <div class="mt-4" style="border-top:1px solid var(--border-light); padding-top:1.5rem;">
            <button type="submit" class="btn btn-primary btn-lg"><?= $isEdit ? 'Simpan Perubahan' : 'Upload Dokumen' ?></button>
        </div>
    </form>
</div>
