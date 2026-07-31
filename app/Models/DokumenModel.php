<?php

class DokumenModel extends Model {

    public function getAllDokumen(?string $search = '') {
        $query = "SELECT * FROM dokumen";
        $params = [];
        if (!empty($search)) {
            $query .= " WHERE judul_dokumen LIKE ? OR nama_file LIKE ?";
            $term = "%$search%";
            $params = [$term, $term];
        }
        $query .= " ORDER BY id_dokumen DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM dokumen WHERE id_dokumen = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(array $data) {
        $stmt = $this->db->prepare(
            "INSERT INTO dokumen (judul_dokumen, nama_file, jenis_file, lokasi_file, versi)
             VALUES (:judul_dokumen, :nama_file, :jenis_file, :lokasi_file, :versi)"
        );
        return $stmt->execute($data);
    }

    public function update($id, array $data) {
        $data['id'] = $id;
        $stmt = $this->db->prepare(
            "UPDATE dokumen SET 
                judul_dokumen = :judul_dokumen,
                nama_file     = :nama_file,
                jenis_file    = :jenis_file,
                lokasi_file   = :lokasi_file,
                versi         = :versi
             WHERE id_dokumen = :id"
        );
        return $stmt->execute($data);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM dokumen WHERE id_dokumen = ?");
        return $stmt->execute([$id]);
    }
}
