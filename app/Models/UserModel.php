<?php

class UserModel extends Model {

    public function findUserByUsername(string $username) {
        $stmt = $this->db->prepare(
            "SELECT u.*, a.nama AS nama_anggota
             FROM users u
             LEFT JOIN anggota a ON u.id_anggota = a.id_anggota
             WHERE u.username = ?"
        );
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id_user = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function authenticate(string $username, string $password) {
        $user = $this->findUserByUsername($username);
        if (!$user) {
            return false;
        }

        if (password_verify($password, $user['password'])) {
            if ($user['is_active'] == 0) {
                return false; // Akun inaktif tidak bisa login
            }
            return [
                'id_user'      => $user['id_user'],
                'username'     => $user['username'],
                'nama_lengkap' => $user['nama_lengkap'],
                'role'         => $user['role'],
                'id_anggota'   => $user['id_anggota']
            ];
        }

        return false;
    }

    public function getAllUsers() {
        $stmt = $this->db->query(
            "SELECT u.id_user, u.username, u.nama_lengkap, u.role, u.id_anggota, u.is_active,
                    a.nama AS nama_anggota, a.email AS email_anggota, u.created_at
             FROM users u
             LEFT JOIN anggota a ON u.id_anggota = a.id_anggota
             ORDER BY u.id_user"
        );
        return $stmt->fetchAll();
    }

    public function isAnggotaHasAccount(string $idAnggota, ?string $exceptIdUser = null): bool {
        $query = "SELECT id_user FROM users WHERE id_anggota = ?";
        $params = [$idAnggota];
        if ($exceptIdUser) {
            $query .= " AND id_user != ?";
            $params[] = $exceptIdUser;
        }
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return (bool)$stmt->fetchColumn();
    }

    public function getAnggotaTanpaAkun() {
        $query = "SELECT a.id_anggota, a.nama, a.email
                  FROM anggota a
                  LEFT JOIN users u ON a.id_anggota = u.id_anggota
                  WHERE u.id_anggota IS NULL
                  ORDER BY a.id_anggota";
        return $this->db->query($query)->fetchAll();
    }

    public function create(array $data) {
        $idAnggota = !empty($data['id_anggota']) ? $data['id_anggota'] : null;

        if ($idAnggota && $this->isAnggotaHasAccount($idAnggota)) {
            return false;
        }

        $stmt = $this->db->prepare(
            "INSERT INTO users (username, password, nama_lengkap, role, id_anggota, is_active)
             VALUES (:username, :password, :nama_lengkap, :role, :id_anggota, 1)"
        );
        $data['password']   = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['id_anggota'] = $idAnggota;
        return $stmt->execute($data);
    }

    public function update($id, array $data) {
        $idAnggota = !empty($data['id_anggota']) ? $data['id_anggota'] : null;

        if ($idAnggota && $this->isAnggotaHasAccount($idAnggota, $id)) {
            return false;
        }

        $params = [
            'username'     => $data['username'],
            'nama_lengkap' => $data['nama_lengkap'],
            'role'         => $data['role'],
            'id_anggota'   => $idAnggota,
            'id_user'      => $id
        ];

        if (!empty($data['password'])) {
            $params['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            $sql = "UPDATE users SET username=:username, password=:password, nama_lengkap=:nama_lengkap, role=:role, id_anggota=:id_anggota WHERE id_user=:id_user";
        } else {
            $sql = "UPDATE users SET username=:username, nama_lengkap=:nama_lengkap, role=:role, id_anggota=:id_anggota WHERE id_user=:id_user";
        }

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function toggleStatus($id) {
        $stmt = $this->db->prepare("UPDATE users SET is_active = NOT is_active WHERE id_user = ?");
        return $stmt->execute([$id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id_user = ?");
        return $stmt->execute([$id]);
    }
}
