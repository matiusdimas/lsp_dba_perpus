<div class="flex-between align-center mb-4">
    <div>
        <h1 style="font-size:1.75rem; font-weight:800;">Proses Pengembalian Buku</h1>
        <p class="text-muted">Konfirmasi pengembalian dan hitung denda secara otomatis.</p>
    </div>
    <a href="index.php?url=peminjaman/index" class="btn btn-secondary">← Batal</a>
</div>

<div class="card" style="max-width: 800px;">
    <form action="index.php?url=pengembalian/simpan" method="POST">
        <div class="form-group" style="max-width: 250px;">
            <label class="form-label">ID Pengembalian</label>
            <input type="text" name="id_pengembalian" class="form-control" value="<?= $data['next_id'] ?>" readonly style="background:#F1F5F9; color:#94A3B8;">
        </div>
        
        <div class="form-group">
            <label class="form-label">Data Peminjaman Aktif</label>
            <select name="id_peminjaman" class="form-control" required style="cursor:pointer;" onchange="updateBukuAndDenda()">
                <option value="">-- Pilih Transaksi Peminjaman --</option>
                <?php foreach($data['peminjaman'] as $p): ?>
                    <?php $selected = ($data['selected_id'] === $p['id_peminjaman']) ? 'selected' : ''; ?>
                    <option value="<?= $p['id_peminjaman'] ?>" 
                            data-buku="<?= htmlspecialchars($p['id_buku']) ?>"
                            data-jatuhtempo="<?= $p['tgl_jatuh_tempo'] ?>"
                            <?= $selected ?>>
                        <?= $p['id_peminjaman'] ?> — <?= htmlspecialchars($p['judul_buku']) ?> (Peminjam: <?= htmlspecialchars($p['nama_peminjam']) ?>) - Jatuh Tempo: <?= $p['tgl_jatuh_tempo'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <input type="hidden" name="id_buku" id="id_buku" value="">
        
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Tanggal Jatuh Tempo</label>
                <input type="date" id="tgl_jatuh_tempo" class="form-control" readonly style="background:#F1F5F9; color:#94A3B8;">
            </div>
            <div class="form-group">
                <label class="form-label">Tanggal Dikembalikan</label>
                <input type="date" name="tgl_kembali" id="tgl_kembali" class="form-control" value="<?= date('Y-m-d') ?>" onchange="updateBukuAndDenda()">
            </div>
        </div>
        
        <div class="form-group" style="background:var(--danger-bg); padding:1.25rem; border-radius:var(--radius-sm); border:1px solid #FECACA;">
            <label class="form-label" style="color:var(--danger);">Denda Keterlambatan Estimasi (Rp 2.000/hari)</label>
            <input type="text" id="estimasi_denda" class="form-control" value="Rp 0" readonly 
                   style="font-weight:800; color:var(--danger); background:white; font-size:1.25rem;">
            <small style="color:var(--danger); display:block; margin-top:0.5rem; font-weight:500;">*Nilai denda aktual dihitung final oleh sistem saat proses dikonfirmasi.</small>
        </div>
        
        <div class="mt-4" style="border-top:1px solid var(--border-light); padding-top:1.5rem;">
            <button type="submit" class="btn btn-primary btn-lg" style="width:100%;">Konfirmasi Pengembalian Buku</button>
        </div>
    </form>
</div>

<script>
function updateBukuAndDenda() {
    let selectElem = document.querySelector('select[name="id_peminjaman"]');
    if(!selectElem.value) {
        document.getElementById('id_buku').value = '';
        document.getElementById('tgl_jatuh_tempo').value = '';
        document.getElementById('estimasi_denda').value = 'Rp 0';
        return;
    }
    
    let selectedOption = selectElem.options[selectElem.selectedIndex];
    document.getElementById('id_buku').value = selectedOption.getAttribute('data-buku');
    
    let tglJatuhTempo = selectedOption.getAttribute('data-jatuhtempo');
    document.getElementById('tgl_jatuh_tempo').value = tglJatuhTempo;
    
    // Hitung denda (estimasi JS)
    let jtDate = new Date(tglJatuhTempo);
    let tglKembaliValue = document.getElementById('tgl_kembali').value;
    
    if (!tglKembaliValue) {
        document.getElementById('estimasi_denda').value = 'Rp 0';
        return;
    }

    let todayDate = new Date(tglKembaliValue);
    
    // Set hours to 0 to compare purely by date
    jtDate.setHours(0,0,0,0);
    todayDate.setHours(0,0,0,0);
    
    let diffTime = todayDate - jtDate;
    let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    let denda = 0;
    if (diffDays > 0) {
        denda = diffDays * 2000;
    }
    
    // Format to IDR manually
    let dendaStr = denda.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    document.getElementById('estimasi_denda').value = 'Rp ' + dendaStr;
}

// Trigger calculation on load if something is already selected
document.addEventListener("DOMContentLoaded", function() {
    let select = document.querySelector('select[name="id_peminjaman"]');
    if (select.value) {
        updateBukuAndDenda();
    }
});
</script>
