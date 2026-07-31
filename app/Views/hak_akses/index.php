<?php include __DIR__ . '/../layouts/_flash.php'; ?>

<div class="flex-between align-center mb-4">
    <div>
        <h1 style="font-size:1.75rem; font-weight:800;">Hak Akses Sistem</h1>
        <p class="text-muted">Informasi batasan fitur yang dapat digunakan oleh masing-masing peran.</p>
    </div>
</div>

<div class="grid-3 mb-4 mt-2">
    <div class="card" style="border-top:4px solid var(--danger);">
        <h2>👑 Administrator</h2>
        <p class="text-muted" style="margin-bottom: 1rem; font-size:0.875rem;">Akses penuh ke seluruh fitur sistem.</p>
        <ul class="quality-list">
            <li>✅ Melihat semua data</li>
            <li>✅ Menambah data baru</li>
            <li>✅ Mengubah data yang ada</li>
            <li>✅ Menghapus data dari sistem</li>
            <li>✅ Mengelola hak akses akun pengguna</li>
        </ul>
    </div>

    <div class="card" style="border-top:4px solid var(--warning);">
        <h2>👷 Petugas</h2>
        <p class="text-muted" style="margin-bottom: 1rem; font-size:0.875rem;">Fokus pada operasional perpustakaan sehari-hari.</p>
        <ul class="quality-list">
            <li>✅ Melihat katalog & data anggota</li>
            <li>✅ Memproses Peminjaman & Pengembalian</li>
            <li>✅ Mengunggah Dokumen Publik</li>
            <li>❌ Tidak dapat menghapus data permanen</li>
            <li>❌ Tidak dapat mengubah hak akses</li>
        </ul>
    </div>

    <div class="card" style="border-top:4px solid var(--success);">
        <h2>👤 Anggota</h2>
        <p class="text-muted" style="margin-bottom: 1rem; font-size:0.875rem;">Akses terbatas untuk penggunaan pribadi.</p>
        <ul class="quality-list">
            <li>✅ Mencari & melihat katalog buku</li>
            <li>✅ Melihat riwayat peminjaman sendiri</li>
            <li>❌ Tidak dapat meminjamkan buku ke orang lain</li>
            <li>❌ Tidak dapat mengubah data perpustakaan</li>
        </ul>
    </div>
</div>

<div class="card mb-4 mt-2">
    <h2>📋 Detail Hak Akses Modul</h2>
    <div class="table-responsive mt-2">
        <table class="table">
            <thead>
                <tr>
                    <th>Modul Aplikasi</th>
                    <th>Fungsi Utama</th>
                    <th>Administrator</th>
                    <th>Petugas</th>
                    <th>Anggota</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $matrix = [
                    ['Data Anggota', 'Melihat Profil Anggota Lain',  true,  true,  false],
                    ['Data Anggota', 'Mendaftarkan Anggota Baru', true,  false, false],
                    ['Katalog Buku', 'Mencari & Melihat Detail Buku', true,  true,  true],
                    ['Katalog Buku', 'Menambah / Mengubah Data Buku', true, true, false],
                    ['Transaksi',    'Menyetujui & Mencatat Peminjaman', true, true, false],
                    ['Transaksi',    'Melihat Riwayat Transaksi Sendiri', true, true, true],
                    ['Manajemen Akun', 'Membuat Akun / Ganti Password User Lain', true, false, false],
                ];
                foreach($matrix as $r): ?>
                <tr>
                    <td style="font-weight:600;"><?= $r[0] ?></td>
                    <td><span class="badge" style="background:#F1F5F9; color:var(--text-main); border:1px solid var(--border); text-transform:none; letter-spacing:0; font-weight:600;"><?= $r[1] ?></span></td>
                    <td><span class="badge <?= $r[2] ? 'success' : 'danger' ?>"><?= $r[2] ? 'Bisa' : 'Tidak' ?></span></td>
                    <td><span class="badge <?= $r[3] ? 'success' : 'danger' ?>"><?= $r[3] ? 'Bisa' : 'Tidak' ?></span></td>
                    <td><span class="badge <?= $r[4] ? 'success' : 'danger' ?>"><?= $r[4] ? 'Bisa' : 'Tidak' ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
