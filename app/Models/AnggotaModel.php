<?php

class AnggotaModel extends Model {

    public function getAllAnggota(?string $search = '') {
        $query = "SELECT * FROM anggota";
        $params = [];
        if (!empty($search)) {
            $query .= " WHERE nama LIKE ? OR id_anggota LIKE ?";
            $term = "%$search%";
            $params = [$term, $term];
        }
        $query .= " ORDER BY id_anggota";
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM anggota WHERE id_anggota = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(array $data) {
        $stmt = $this->db->prepare(
            "INSERT INTO anggota (id_anggota, nama, email, no_hp, alamat)
             VALUES (:id_anggota, :nama, :email, :no_hp, :alamat)"
        );
        return $stmt->execute($data);
    }

    public function update($id, array $data) {
        $data['id_anggota'] = $id;
        $stmt = $this->db->prepare(
            "UPDATE anggota SET
                nama    = :nama,
                email   = :email,
                no_hp   = :no_hp,
                alamat  = :alamat
             WHERE id_anggota = :id_anggota"
        );
        return $stmt->execute($data);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM anggota WHERE id_anggota = ?");
        return $stmt->execute([$id]);
    }

    public function importCSV($filePath) {
        if (!file_exists($filePath)) {
            return false;
        }

        $file = fopen($filePath, 'r');
        fgetcsv($file); // skip header

        $successCount = 0;
        $nextIdStr = $this->getNextId(); // "AG-001"
        $nextNum = (int)substr($nextIdStr, 3);

        while (($data = fgetcsv($file)) !== FALSE) {
            if (count($data) >= 4) {
                try {
                    $currentId = sprintf('AG-%03d', $nextNum);
                    $stmt = $this->db->prepare(
                        "INSERT INTO anggota (id_anggota, nama, email, no_hp, alamat)
                         VALUES (?, ?, ?, ?, ?)
                         ON DUPLICATE KEY UPDATE nama=VALUES(nama), no_hp=VALUES(no_hp), alamat=VALUES(alamat)"
                    );
                    $stmt->execute([$currentId, $data[0], $data[1], $data[2], $data[3]]);
                    
                    // Increment ID only if it was actually inserted
                    // (rowCount is 1 for insert, 2 for update ON DUPLICATE)
                    if ($stmt->rowCount() === 1) {
                        $nextNum++;
                    }
                    $successCount++;
                } catch (PDOException $e) {
                    // Abaikan baris dengan data bermasalah
                }
            }
        }
        fclose($file);
        return $successCount;
    }

    public function checkDataGanda() {
        $query = "SELECT email, COUNT(*) as jumlah FROM anggota GROUP BY email HAVING jumlah > 1";
        return $this->db->query($query)->fetchAll();
    }

    public function cleanDataGanda() {
        $query = "DELETE FROM anggota WHERE id_anggota NOT IN
                  (SELECT min_id FROM (SELECT MIN(id_anggota) as min_id FROM anggota GROUP BY email) as t)";
        return $this->db->exec($query);
    }

    public function checkDataInvalid() {
        $query = "SELECT * FROM anggota WHERE nama IS NULL OR nama = '' OR email IS NULL OR email NOT LIKE '%@%'";
        return $this->db->query($query)->fetchAll();
    }

    public function getNextId(): string {
        $stmt = $this->db->query("SELECT id_anggota FROM anggota ORDER BY id_anggota DESC LIMIT 1");
        $last = $stmt->fetchColumn();
        $next = $last ? (int)substr($last, 3) + 1 : 1;
        return sprintf('AG-%03d', $next);
    }
}
