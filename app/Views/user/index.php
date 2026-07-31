<?php include __DIR__ . '/../layouts/_flash.php'; ?>

<div class="flex-between align-center mb-4">
    <div>
        <h1 style="font-size:1.75rem; font-weight:800;">Manajemen Akun Pengguna</h1>
    </div>
</div>

<div class="card mb-4" style="padding: 1rem 1.5rem;">
    <form action="index.php" method="GET" class="search-form">
        <input type="hidden" name="url" value="user/index">
        <input type="text" name="search" placeholder="🔍 Cari berdasarkan username atau nama lengkap..." 
               value="<?= htmlspecialchars($data['keyword'] ?? '') ?>" class="form-control" style="flex:1;">
        <button type="submit" class="btn btn-primary">Cari</button>
        <?php if(!empty($data['keyword'])): ?>
        <a href="index.php?url=user/index" class="btn btn-secondary">Reset</a>
        <?php endif; ?>
    </form>
</div>

<div class="grid-2 mb-4 mt-2">
    <div class="card">
        <h2>Buat Akun Pengguna Baru</h2>
        <form action="index.php?url=user/simpan" method="POST" style="margin-top:1.25rem;">
            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="contoh: dimas_p" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter..." required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama lengkap pengguna" required>
            </div>
            
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Peran (Role)</label>
                    <select name="role" class="form-control" required style="cursor:pointer;">
                        <option value="Anggota">Anggota</option>
                        <option value="Petugas">Petugas</option>
                        <option value="Administrator">Administrator</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Hubungkan dengan Data Anggota</label>
                    <select name="id_anggota" class="form-control" style="cursor:pointer;">
                        <option value="">-- Tanpa Tautan (Non-Anggota) --</option>
                        <?php foreach($data['anggota'] as $a): ?>
                        <option value="<?= $a['id_anggota'] ?>">
                            <?= $a['id_anggota'] ?> — <?= htmlspecialchars($a['nama']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <small style="color:var(--text-light); font-size:0.75rem; display:block; margin-top:0.35rem;">
                        *Hanya tampilkan anggota tanpa akun.
                    </small>
                </div>
            </div>
            
            <div class="mt-2" style="border-top:1px solid var(--border-light); padding-top:1.5rem;">
                <button type="submit" class="btn btn-primary" style="width:100%;">Buat Akun Baru</button>
            </div>
        </form>
    </div>

    <div class="card">
        <h2>Aturan Peran Akun</h2>
        <ul class="quality-list mt-2">
            <li>
                <strong>👑 Administrator & 👷 Petugas</strong><br>
                <span class="text-muted" style="font-size:0.875rem;">Akun khusus staf perpustakaan. Akun ini berdiri sendiri dan tidak perlu dihubungkan dengan data pendaftaran anggota.</span>
            </li>
            <li>
                <strong>👤 Anggota</strong><br>
                <span class="text-muted" style="font-size:0.875rem;">Akun khusus untuk peminjam buku. Dapat digunakan untuk melihat riwayat peminjaman pribadi dan mencari katalog buku perpustakaan.</span>
            </li>
        </ul>
    </div>
</div>

<div class="card mt-2">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Nama Lengkap</th>
                    <th>Peran</th>
                    <th>Tautan Anggota</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['users'] as $u): ?>
                <tr>
                    <td><code><?= htmlspecialchars($u['username']) ?></code></td>
                    <td style="font-weight:600;"><?= htmlspecialchars($u['nama_lengkap']) ?></td>
                    <td>
                        <?php
                        $badgeClass = 'info';
                        if ($u['role'] === 'Administrator') $badgeClass = 'danger';
                        if ($u['role'] === 'Petugas') $badgeClass = 'warning';
                        if ($u['role'] === 'Anggota') $badgeClass = 'success';
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= $u['role'] ?></span>
                    </td>
                    <td>
                        <?php if($u['id_anggota']): ?>
                            <span class="badge" style="background:#F1F5F9; color:var(--text-main); border:1px solid var(--border);">🔗 <?= $u['id_anggota'] ?></span>
                        <?php else: ?>
                            <span class="text-muted" style="font-size:0.8125rem;">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($u['is_active'] == 1): ?>
                            <span class="badge success">Aktif</span>
                        <?php else: ?>
                            <span class="badge danger" style="opacity:0.8;">Inaktif</span>
                        <?php endif; ?>
                    </td>
                    <td class="action-cell">
                        <?php if($u['username'] !== 'admin'): ?>
                        <a href="index.php?url=user/edit/<?= $u['id_user'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                        <a href="index.php?url=user/toggleStatus/<?= $u['id_user'] ?>" class="btn btn-sm btn-<?= $u['is_active'] ? 'danger' : 'success' ?>"
                           onclick="return confirm('Yakin ingin <?= $u['is_active'] ? 'menonaktifkan' : 'mengaktifkan' ?> akun ini?')">
                           <?= $u['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?>
                        </a>
                        <?php else: ?>
                        <span class="badge" style="background:var(--border-light); color:var(--text-muted);">🔒 Superadmin</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
