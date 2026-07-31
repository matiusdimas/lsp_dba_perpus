<?php include __DIR__ . '/../layouts/_flash.php'; ?>

<div class="flex-between align-center mb-4">
    <div>
        <h1 style="font-size:1.75rem; font-weight:800;">Edit Akun Pengguna</h1>
        <p class="text-muted">Perbarui data login atau relasi anggota.</p>
    </div>
    <a href="index.php?url=user/index" class="btn btn-secondary">← Kembali</a>
</div>

<div class="card" style="max-width: 600px;">
    <form action="index.php?url=user/update/<?= $data['user_edit']['id_user'] ?>" method="POST">
        <div class="form-group">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($data['user_edit']['username']) ?>" required>
        </div>
        
        <div class="form-group">
            <label class="form-label">Password <span class="text-muted" style="font-weight:400;">(Kosongkan jika tidak ingin mengubah)</span></label>
            <input type="password" name="password" class="form-control" placeholder="••••••••">
        </div>
        
        <div class="form-group">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($data['user_edit']['nama_lengkap']) ?>" required>
        </div>
        
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Peran (Role)</label>
                <select name="role" class="form-control" required style="cursor:pointer;">
                    <option value="Anggota" <?= $data['user_edit']['role'] === 'Anggota' ? 'selected' : '' ?>>Anggota</option>
                    <option value="Petugas" <?= $data['user_edit']['role'] === 'Petugas' ? 'selected' : '' ?>>Petugas</option>
                    <option value="Administrator" <?= $data['user_edit']['role'] === 'Administrator' ? 'selected' : '' ?>>Administrator</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Tautkan ke Anggota (1-to-1)</label>
                <select name="id_anggota" class="form-control" style="cursor:pointer;">
                    <option value="">-- Tanpa Tautan (Non-Anggota) --</option>
                    <?php foreach($data['anggota'] as $a): ?>
                    <option value="<?= $a['id_anggota'] ?>" <?= $data['user_edit']['id_anggota'] === $a['id_anggota'] ? 'selected' : '' ?>>
                        <?= $a['id_anggota'] ?> — <?= htmlspecialchars($a['nama']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div class="mt-4" style="border-top:1px solid var(--border-light); padding-top:1.5rem;">
            <button type="submit" class="btn btn-primary btn-lg" style="width:100%;">Simpan Perubahan</button>
        </div>
    </form>
</div>
