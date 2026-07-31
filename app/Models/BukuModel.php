<?php

class BukuModel extends Model {

    public function getAllBukuTersedia(?string $search = '') {
        $query = "SELECT b.id_buku, b.judul, k.nama_kategori, b.stok, b.penulis, b.penerbit
                  FROM buku b
                  JOIN kategori k ON b.id_kategori = k.id_kategori
                  WHERE b.stok > 0";
        $params = [];
        if (!empty($search)) {
            $query .= " AND (b.judul LIKE ? OR b.penulis LIKE ? OR b.id_buku LIKE ?)";
            $term = "%$search%";
            $params = [$term, $term, $term];
        }
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getAllBuku(?string $search = '') {
        $query = "SELECT b.id_buku, b.judul, k.nama_kategori, b.stok, b.penulis, b.penerbit
                  FROM buku b
                  JOIN kategori k ON b.id_kategori = k.id_kategori";
        $params = [];
        if (!empty($search)) {
            $query .= " WHERE b.judul LIKE ? OR b.penulis LIKE ? OR b.id_buku LIKE ?";
            $term = "%$search%";
            $params = [$term, $term, $term];
        }
        $query .= " ORDER BY b.id_buku";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function find($id) {
        $query = "SELECT * FROM buku WHERE id_buku = ?";
        $stmt  = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(array $data) {
        $stmt = $this->db->prepare(
            "INSERT INTO buku (id_buku, id_kategori, judul, penulis, penerbit, stok)
             VALUES (:id_buku, :id_kategori, :judul, :penulis, :penerbit, :stok)"
        );
        return $stmt->execute($data);
    }

    public function update($id, array $data) {
        $data['id_buku'] = $id;
        $stmt = $this->db->prepare(
            "UPDATE buku SET
                id_kategori = :id_kategori,
                judul       = :judul,
                penulis     = :penulis,
                penerbit    = :penerbit,
                stok        = :stok
             WHERE id_buku  = :id_buku"
        );
        return $stmt->execute($data);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM buku WHERE id_buku = ?");
        return $stmt->execute([$id]);
    }

    public function getAllKategori() {
        return $this->db->query("SELECT * FROM kategori")->fetchAll();
    }

    public function fixStokNegatif() {
        return $this->db->exec("UPDATE buku SET stok = 0 WHERE stok < 0");
    }

    public function getNextId(): string {
        $stmt = $this->db->query("SELECT id_buku FROM buku ORDER BY id_buku DESC LIMIT 1");
        $last = $stmt->fetchColumn();
        $next = $last ? (int)substr($last, 3) + 1 : 1;
        return sprintf('BK-%03d', $next);
    }
}
