<?php

class PengembalianModel extends Model {

    public function getAllPengembalian(?string $idAnggota = null, ?string $search = null) {
        $where = [];
        $params = [];
        if ($idAnggota) {
            $where[] = "pm.id_anggota = ?";
            $params[] = $idAnggota;
        }
        if ($search) {
            $where[] = "(a.nama LIKE ? OR b.judul LIKE ? OR pg.id_pengembalian LIKE ?)";
            $term = "%$search%";
            $params[] = $term;
            $params[] = $term;
            $params[] = $term;
        }

        $whereClause = count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "";

        $query = "SELECT pg.id_pengembalian, pm.id_peminjaman, a.nama AS nama_peminjam, b.judul AS judul_buku,
                         pg.tgl_kembali, pg.denda
                  FROM pengembalian pg
                  JOIN peminjaman pm ON pg.id_peminjaman = pm.id_peminjaman
                  JOIN anggota a ON pm.id_anggota = a.id_anggota
                  JOIN buku b ON pg.id_buku = b.id_buku
                  $whereClause
                  ORDER BY pg.tgl_kembali DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function create(array $data) {
        try {
            $this->db->beginTransaction();

            // 1. Insert pengembalian
            $stmt = $this->db->prepare(
                "INSERT INTO pengembalian (id_pengembalian, id_peminjaman, id_buku, tgl_kembali, denda)
                 VALUES (:id_pengembalian, :id_peminjaman, :id_buku, :tgl_kembali, :denda)"
            );
            $stmt->execute($data);

            // 2. Tambah stok buku kembali
            $stmtStok = $this->db->prepare("UPDATE buku SET stok = stok + 1 WHERE id_buku = ?");
            $stmtStok->execute([$data['id_buku']]);

            // 3. Update status peminjaman menjadi Selesai
            $stmtStatus = $this->db->prepare("UPDATE peminjaman SET status = 'Selesai' WHERE id_peminjaman = ?");
            $stmtStatus->execute([$data['id_peminjaman']]);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function hitungDenda(string $tglJatuhTempo, string $tglKembali): float {
        $dendaPerHari = 2000;
        $jatuhTempo   = new DateTime($tglJatuhTempo);
        $kembali      = new DateTime($tglKembali);
        $selisih      = $jatuhTempo->diff($kembali)->days;
        $terlambat    = $kembali > $jatuhTempo;
        return $terlambat ? $selisih * $dendaPerHari : 0;
    }

    public function getPeminjamanAktifForReturn() {
        $query = "SELECT p.id_peminjaman, p.id_anggota, a.nama AS nama_peminjam,
                         p.id_buku, b.judul AS judul_buku, p.tgl_pinjam,
                         p.tgl_jatuh_tempo, p.status
                  FROM peminjaman p
                  JOIN anggota a ON p.id_anggota = a.id_anggota
                  JOIN buku b    ON p.id_buku = b.id_buku
                  WHERE p.status = 'Dipinjam'
                  ORDER BY p.tgl_jatuh_tempo ASC";
        return $this->db->query($query)->fetchAll();
    }

    public function getNextId(): string {
        $year = date('Y');
        $stmt = $this->db->query(
            "SELECT id_pengembalian FROM pengembalian WHERE id_pengembalian LIKE 'KB-{$year}-%' ORDER BY id_pengembalian DESC LIMIT 1"
        );
        $last = $stmt->fetchColumn();
        $next = $last ? (int)substr($last, -3) + 1 : 1;
        return sprintf('KB-%s-%03d', $year, $next);
    }
}
