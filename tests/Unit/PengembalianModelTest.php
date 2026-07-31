<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../app/Core/Model.php';
require_once __DIR__ . '/../../app/Models/PengembalianModel.php';

class PengembalianModelTest extends TestCase {

    private function createPdoStub(array $fetchData = []): PDO {
        $stmtStub = $this->createStub(PDOStatement::class);
        $stmtStub->method('execute')->willReturn(true);
        $stmtStub->method('fetchAll')->willReturn($fetchData);
        $stmtStub->method('fetchColumn')->willReturn('KB-2026-001');

        $pdoStub = $this->createStub(PDO::class);
        $pdoStub->method('prepare')->willReturn($stmtStub);
        $pdoStub->method('query')->willReturn($stmtStub);
        $pdoStub->method('beginTransaction')->willReturn(true);
        $pdoStub->method('commit')->willReturn(true);
        $pdoStub->method('rollBack')->willReturn(true);

        return $pdoStub;
    }

    public function testHitungDendaNolJikaTepatWaktu(): void {
        $model = new PengembalianModel($this->createPdoStub());
        $denda = $model->hitungDenda('2026-08-08', '2026-08-08');
        $this->assertEquals(0, $denda);
    }

    public function testHitungDendaNolJikaLebihCepat(): void {
        $model = new PengembalianModel($this->createPdoStub());
        $denda = $model->hitungDenda('2026-08-08', '2026-08-05');
        $this->assertEquals(0, $denda);
    }

    public function testSearchPengembalian(): void {
        $mockData = [
            ['id_pengembalian' => 1, 'nama_peminjam' => 'Dimas', 'judul_buku' => 'PHP Dasar']
        ];
        
        $stmtStub = $this->createStub(PDOStatement::class);
        $stmtStub->method('execute')->willReturn(true);
        $stmtStub->method('fetchAll')->willReturn($mockData);

        $pdoStub = $this->createStub(PDO::class);
        $pdoStub->method('prepare')->willReturn($stmtStub);
        
        $model = new PengembalianModel($pdoStub);
        $result = $model->getAllPengembalian(null, 'Dimas');
        
        $this->assertCount(1, $result);
        $this->assertEquals('Dimas', $result[0]['nama_peminjam']);
    }

    public function testHitungDendaSatuHari(): void {
        $model = new PengembalianModel($this->createPdoStub());
        $denda = $model->hitungDenda('2026-08-08', '2026-08-09');
        $this->assertEquals(2000, $denda);
    }

    public function testHitungDendaLimaBelas(): void {
        $model = new PengembalianModel($this->createPdoStub());
        $denda = $model->hitungDenda('2026-08-01', '2026-08-16');
        $this->assertEquals(30000, $denda);
    }

    public function testCreatePengembalian(): void {
        $model = new PengembalianModel($this->createPdoStub());
        $result = $model->create([
            'id_pengembalian' => 'KB-2026-001',
            'id_peminjaman'   => 'PJ-2026-001',
            'id_buku'         => 'BK-001',
            'tgl_kembali'     => '2026-08-08',
            'denda'           => 0.00,
        ]);
        $this->assertTrue($result);
    }

    public function testGetPeminjamanAktifForReturnIncludesBookInfo(): void {
        $data = [['id_peminjaman' => 'PJ-2026-001', 'nama_peminjam' => 'Dimas', 'id_buku' => 'BK-001', 'judul_buku' => 'Pemrograman Web']];
        $model = new PengembalianModel($this->createPdoStub($data));
        $result = $model->getPeminjamanAktifForReturn();
        $this->assertCount(1, $result);
        $this->assertEquals('BK-001', $result[0]['id_buku']);
        $this->assertEquals('Pemrograman Web', $result[0]['judul_buku']);
    }

    public function testGetNextId(): void {
        $model = new PengembalianModel($this->createPdoStub());
        $this->assertStringStartsWith('KB-', $model->getNextId());
    }
}
