<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../app/Core/Model.php';
require_once __DIR__ . '/../../app/Models/PeminjamanModel.php';

class PeminjamanModelTest extends TestCase {

    private function createPdoStub(array $fetchData = []): PDO {
        $stmtStub = $this->createStub(PDOStatement::class);
        $stmtStub->method('execute')->willReturn(true);
        $stmtStub->method('fetchAll')->willReturn($fetchData);
        $stmtStub->method('fetchColumn')->willReturn('PJ-2026-001');

        $pdoStub = $this->createStub(PDO::class);
        $pdoStub->method('prepare')->willReturn($stmtStub);
        $pdoStub->method('query')->willReturn($stmtStub);
        $pdoStub->method('beginTransaction')->willReturn(true);
        $pdoStub->method('commit')->willReturn(true);
        $pdoStub->method('rollBack')->willReturn(true);

        return $pdoStub;
    }

    public function testGetPeminjamanAktif(): void {
        $mockData = [
            ['id_peminjaman' => 'PJ-2026-001', 'nama_peminjam' => 'Dimas', 'status' => 'Dipinjam']
        ];
        $model = new PeminjamanModel($this->createPdoStub($mockData));
        $result = $model->getPeminjamanAktif();
        $this->assertCount(1, $result);
        $this->assertEquals('Dipinjam', $result[0]['status']);
    }

    public function testGetAllPeminjaman(): void {
        $mockData = [
            ['id_peminjaman' => 'PJ-2026-001', 'nama_peminjam' => 'Dimas', 'status' => 'Dipinjam'],
            ['id_peminjaman' => 'PJ-2026-002', 'nama_peminjam' => 'Akbar', 'status' => 'Selesai']
        ];
        $model = new PeminjamanModel($this->createPdoStub($mockData));
        $result = $model->getAllPeminjaman();
        $this->assertCount(2, $result);
    }

    public function testGetAllPeminjamanWithStatusFilter(): void {
        $mockData = [
            ['id_peminjaman' => 'PJ-2026-001', 'nama_peminjam' => 'Dimas', 'status' => 'Dipinjam']
        ];
        // Simulate query filtering by returning only the Dipinjam row
        $model = new PeminjamanModel($this->createPdoStub($mockData));
        $result = $model->getAllPeminjaman(null, 'Dipinjam');
        $this->assertCount(1, $result);
        $this->assertEquals('Dipinjam', $result[0]['status']);
    }

    public function testSearchPeminjaman(): void {
        $mockData = [
            ['id_peminjaman' => 'PJ-2026-001', 'nama_peminjam' => 'Dimas', 'judul_buku' => 'PHP Dasar']
        ];
        
        $stmtStub = $this->createStub(PDOStatement::class);
        $stmtStub->method('execute')->willReturn(true);
        $stmtStub->method('fetchAll')->willReturn($mockData);
        
        $pdoStub = $this->createStub(PDO::class);
        $pdoStub->method('prepare')->willReturn($stmtStub);
        
        $model = new PeminjamanModel($pdoStub);
        $result = $model->getAllPeminjaman(null, null, 'PHP Dasar');
        
        $this->assertCount(1, $result);
        $this->assertEquals('PHP Dasar', $result[0]['judul_buku']);
    }

    public function testCreatePeminjaman(): void {
        $model = new PeminjamanModel($this->createPdoStub());
        $result = $model->create([
            'id_peminjaman'   => 'PJ-2026-003',
            'id_anggota'      => 'AG-001',
            'id_buku'         => 'BK-001',
            'tgl_pinjam'      => '2026-08-01',
            'tgl_jatuh_tempo' => '2026-08-08',
        ]);
        $this->assertTrue($result, "Peminjaman harus berhasil dibuat tanpa mismatch parameter.");
    }

    public function testGetNextIdFormat(): void {
        $model = new PeminjamanModel($this->createPdoStub());
        $nextId = $model->getNextId();
        $this->assertStringStartsWith('PJ-', $nextId);
    }
}
