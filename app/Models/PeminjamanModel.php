<?php

class PeminjamanModel extends Model {

    public function getPeminjamanAktif(?string $idAnggota = null) {
        if ($idAnggota) {
            $stmt = $this->db->prepare(
                "SELECT p.id_peminjaman, a.nama AS nama_peminjam, b.judul AS judul_buku,
                        p.tgl_pinjam, p.tgl_jatuh_tempo, p.status
                 FROM peminjaman p
                 JOIN anggota a ON p.id_anggota = a.id_anggota
                 JOIN buku b    ON p.id_buku = b.id_buku
                 WHERE p.status = 'Dipinjam' AND p.id_anggota = ?
                 ORDER BY p.tgl_jatuh_tempo ASC"
            );
            $stmt->execute([$idAnggota]);
            return $stmt->fetchAll();
        }

        $query = "SELECT p.id_peminjaman, a.nama AS nama_peminjam, b.judul AS judul_buku,
                         p.tgl_pinjam, p.tgl_jatuh_tempo, p.status
                  FROM peminjaman p
                  JOIN anggota a ON p.id_anggota = a.id_anggota
                  JOIN buku b    ON p.id_buku = b.id_buku
                  WHERE p.status = 'Dipinjam'
                  ORDER BY p.tgl_jatuh_tempo ASC";
        return $this->db->query($query)->fetchAll();
    }

    public function getAllPeminjaman(?string $idAnggota = null, ?string $status = null, ?string $search = null) {
        $where = [];
        $params = [];
        if ($idAnggota) {
            $where[] = "p.id_anggota = ?";
            $params[] = $idAnggota;
        }
        if ($status) {
            $where[] = "p.status = ?";
            $params[] = $status;
        }
        if ($search) {
            $where[] = "(a.nama LIKE ? OR b.judul LIKE ? OR p.id_peminjaman LIKE ?)";
            $term = "%$search%";
            $params[] = $term;
            $params[] = $term;
            $params[] = $term;
        }

        $whereClause = count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "";

        $query = "SELECT p.id_peminjaman, a.nama AS nama_peminjam, b.judul AS judul_buku,
                         p.tgl_pinjam, p.tgl_jatuh_tempo, p.status
                  FROM peminjaman p
                  JOIN anggota a ON p.id_anggota = a.id_anggota
                  JOIN buku b    ON p.id_buku = b.id_buku
                  $whereClause
                  ORDER BY p.tgl_pinjam DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function create(array $data) {
        try {
            $this->db->beginTransaction();

            // 1. Insert peminjaman header dengan referensi id_buku
            $stmt = $this->db->prepare(
                "INSERT INTO peminjaman (id_peminjaman, id_anggota, id_buku, tgl_pinjam, tgl_jatuh_tempo, status)
                 VALUES (:id_peminjaman, :id_anggota, :id_buku, :tgl_pinjam, :tgl_jatuh_tempo, 'Dipinjam')"
            );
            $stmt->execute([
                'id_peminjaman'   => $data['id_peminjaman'],
                'id_anggota'      => $data['id_anggota'],
                'id_buku'         => $data['id_buku'],
                'tgl_pinjam'      => $data['tgl_pinjam'],
                'tgl_jatuh_tempo' => $data['tgl_jatuh_tempo'],
            ]);

            // 2. Kurangi stok buku
            $stmtStok = $this->db->prepare("UPDATE buku SET stok = stok - 1 WHERE id_buku = ? AND stok > 0");
            $stmtStok->execute([$data['id_buku']]);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getNextId(): string {
        $year = date('Y');
        $stmt = $this->db->query(
            "SELECT id_peminjaman FROM peminjaman WHERE id_peminjaman LIKE 'PJ-{$year}-%' ORDER BY id_peminjaman DESC LIMIT 1"
        );
        $last = $stmt->fetchColumn();
        $next = $last ? (int)substr($last, -3) + 1 : 1;
        return sprintf('PJ-%s-%03d', $year, $next);
    }
}
