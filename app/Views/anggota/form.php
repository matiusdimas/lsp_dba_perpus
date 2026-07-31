<?php $isEdit = isset($data['anggota']) && $data['anggota']; ?>

<div class="flex-between align-center mb-4">
    <div>
        <h1 style="font-size:1.75rem; font-weight:800;"><?= $isEdit ? 'Edit Anggota' : 'Tambah Anggota Baru' ?></h1>
        <p class="text-muted">Lengkapi profil anggota perpustakaan.</p>
    </div>
    <a href="index.php?url=anggota/index" class="btn btn-secondary">← Batal</a>
</div>

<div class="card" style="max-width: 800px;">
    <?php
    $action = $isEdit
        ? "index.php?url=anggota/update/{$data['anggota']['id_anggota']}"
        : "index.php?url=anggota/simpan";
    ?>
    <form action="<?= $action ?>" method="POST">
        <div class="form-group" style="max-width: 250px;">
            <label class="form-label">ID Anggota</label>
            <input type="text" name="id_anggota" class="form-control"
                   value="<?= $isEdit ? $data['anggota']['id_anggota'] : ($data['next_id'] ?? '') ?>"
                   <?= $isEdit ? 'readonly style="background:#F1F5F9; color:#94A3B8;"' : '' ?> required>
        </div>
        
        <div class="form-group">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" placeholder="Nama lengkap sesuai KTP/KTM..."
                   value="<?= $isEdit ? htmlspecialchars($data['anggota']['nama']) : '' ?>" required>
        </div>
        
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Email Aktif</label>
                <input type="email" name="email" class="form-control" placeholder="nama@email.com"
                       value="<?= $isEdit ? htmlspecialchars($data['anggota']['email']) : '' ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Nomor Handphone</label>
                <input type="text" name="no_hp" class="form-control" placeholder="08..."
                       value="<?= $isEdit ? htmlspecialchars($data['anggota']['no_hp']) : '' ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label">Alamat Lengkap</label>
            <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat domisili saat ini..."><?= $isEdit ? htmlspecialchars($data['anggota']['alamat']) : '' ?></textarea>
        </div>
        
        <div class="mt-4" style="border-top:1px solid var(--border-light); padding-top:1.5rem;">
            <button type="submit" class="btn btn-primary btn-lg"><?= $isEdit ? 'Simpan Perubahan' : 'Simpan Profil Anggota' ?></button>
        </div>
    </form>
</div>
