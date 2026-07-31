<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../app/Core/Model.php';
require_once __DIR__ . '/../../app/Models/UserModel.php';

class UserModelTest extends TestCase {

    private function createPdoStub(array $fetchData = []): PDO {
        $stmtStub = $this->createStub(PDOStatement::class);
        $stmtStub->method('execute')->willReturn(true);
        $stmtStub->method('fetchAll')->willReturn($fetchData);
        $stmtStub->method('fetchColumn')->willReturn('1');
        $stmtStub->method('fetch')->willReturn($fetchData[0] ?? false);

        $pdoStub = $this->createStub(PDO::class);
        $pdoStub->method('prepare')->willReturn($stmtStub);
        $pdoStub->method('query')->willReturn($stmtStub);
        $pdoStub->method('exec')->willReturn(1);

        return $pdoStub;
    }

    public function testFindUserByUsernameReturnsUserWithAnggotaLink(): void {
        $hashed = password_hash('anggota123', PASSWORD_BCRYPT);
        $userData = [[
            'id_user'      => 3,
            'username'     => 'anggota',
            'password'     => $hashed,
            'nama_lengkap' => 'Dimas Prasetia',
            'role'         => 'Anggota',
            'id_anggota'   => 'AG-001'
        ]];

        $model = new UserModel($this->createPdoStub($userData));
        $user = $model->findUserByUsername('anggota');

        $this->assertNotFalse($user);
        $this->assertEquals('anggota', $user['username']);
        $this->assertEquals('AG-001', $user['id_anggota']);
    }

    public function testSearchUser(): void {
        $userData = [
            ['id_user' => 3, 'username' => 'anggota', 'nama_lengkap' => 'Dimas Prasetia']
        ];
        $model = new UserModel($this->createPdoStub($userData));
        $result = $model->getAllUsers('Dimas');

        $this->assertCount(1, $result);
        $this->assertEquals('Dimas Prasetia', $result[0]['nama_lengkap']);
    }

    public function testAuthenticateWithCorrectPasswordReturnsUserArrayWithIdAnggota(): void {
        $hashed = password_hash('anggota123', PASSWORD_BCRYPT);
        $userData = [[
            'id_user'      => 3,
            'username'     => 'anggota',
            'password'     => $hashed,
            'nama_lengkap' => 'Dimas Prasetia',
            'role'         => 'Anggota',
            'id_anggota'   => 'AG-001',
            'is_active'    => 1
        ]];

        $model = new UserModel($this->createPdoStub($userData));
        $authenticated = $model->authenticate('anggota', 'anggota123');

        $this->assertNotFalse($authenticated);
        $this->assertEquals('anggota', $authenticated['username']);
        $this->assertEquals('Anggota', $authenticated['role']);
        $this->assertEquals('AG-001', $authenticated['id_anggota']);
    }

    public function testAuthenticateInactiveUserReturnsFalse(): void {
        $hashed = password_hash('anggota123', PASSWORD_BCRYPT);
        $userData = [[
            'id_user'      => 3,
            'username'     => 'anggota',
            'password'     => $hashed,
            'nama_lengkap' => 'Dimas Prasetia',
            'role'         => 'Anggota',
            'id_anggota'   => 'AG-001',
            'is_active'    => 0 // INACTIVE
        ]];

        $model = new UserModel($this->createPdoStub($userData));
        $authenticated = $model->authenticate('anggota', 'anggota123');

        $this->assertFalse($authenticated, "Akun inaktif harus gagal login.");
    }

    public function testAuthenticateWithWrongPasswordReturnsFalse(): void {
        $hashed = password_hash('admin123', PASSWORD_BCRYPT);
        $userData = [[
            'id_user'      => 1,
            'username'     => 'admin',
            'password'     => $hashed,
            'nama_lengkap' => 'Administrator Sistem',
            'role'         => 'Administrator',
            'id_anggota'   => null
        ]];

        $model = new UserModel($this->createPdoStub($userData));
        $authenticated = $model->authenticate('admin', 'wrongpass');

        $this->assertFalse($authenticated);
    }

    public function testIsAnggotaHasAccountReturnsTrueForLinkedAnggota(): void {
        $model = new UserModel($this->createPdoStub());
        $this->assertTrue($model->isAnggotaHasAccount('AG-001'), "Harus mendeteksi bahwa AG-001 sudah memiliki akun.");
    }

    public function testGetAnggotaTanpaAkunReturnsOnlyUnlinkedMembers(): void {
        $data = [['id_anggota' => 'AG-002', 'nama' => 'Akbar', 'email' => 'akbar@gmail.com']];
        $model = new UserModel($this->createPdoStub($data));
        $result = $model->getAnggotaTanpaAkun();
        $this->assertCount(1, $result);
        $this->assertEquals('AG-002', $result[0]['id_anggota']);
    }

    public function testCreateUserFailsIfAnggotaAlreadyHasAccountOneToOne(): void {
        $model = new UserModel($this->createPdoStub());
        $result = $model->create([
            'username'     => 'duplicate_user',
            'password'     => 'secret',
            'nama_lengkap' => 'Duplikat User',
            'role'         => 'Anggota',
            'id_anggota'   => 'AG-001'
        ]);
        $this->assertFalse($result, "Membuat akun kedua untuk anggota yang sama harus gagal (constraint).");
    }

    public function testDeleteUser(): void {
        $model = new UserModel($this->createPdoStub());
        $result = $model->delete(1);
        $this->assertTrue($result);
    }

    public function testUpdateUser(): void {
        $model = new UserModel($this->createPdoStub());
        $result = $model->update(1, [
            'username'     => 'admin_edit',
            'password'     => 'newpass',
            'nama_lengkap' => 'Admin Edited',
            'role'         => 'Administrator',
            'id_anggota'   => ''
        ]);
        $this->assertTrue($result);
    }

    public function testToggleStatus(): void {
        $model = new UserModel($this->createPdoStub());
        $result = $model->toggleStatus(1);
        $this->assertTrue($result);
    }
}
