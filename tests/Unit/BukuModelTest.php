<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../app/Core/Model.php';
require_once __DIR__ . '/../../app/Models/BukuModel.php';

class BukuModelTest extends TestCase {

    private function createPdoStub(array $fetchData = []): PDO {
        $stmtStub = $this->createStub(PDOStatement::class);
        $stmtStub->method('execute')->willReturn(true);
        $stmtStub->method('fetchAll')->willReturn($fetchData);
        $stmtStub->method('fetch')->willReturn($fetchData[0] ?? false);

        $pdoStub = $this->createStub(PDO::class);
        $pdoStub->method('prepare')->willReturn($stmtStub);
        $pdoStub->method('query')->willReturn($stmtStub);
        $pdoStub->method('exec')->willReturn(1);

        return $pdoStub;
    }

    public function testGetAllBukuTersediaOnlyReturnsPositiveStock(): void {
        $mockData = [
            ['id_buku' => 'BK-001', 'judul' => 'Pemrograman Web', 'nama_kategori' => 'Informatika', 'stok' => 5]
        ];
        $bukuModel = new BukuModel($this->createPdoStub($mockData));
        $result = $bukuModel->getAllBukuTersedia();
        $this->assertCount(1, $result);
        $this->assertEquals('BK-001', $result[0]['id_buku']);
    }

    public function testSearchBukuByJudul(): void {
        $mockData = [
            ['id_buku' => 'BK-001', 'judul' => 'PHP Dasar', 'penulis' => 'Dimas']
        ];
        
        $stmtStub = $this->createStub(PDOStatement::class);
        $stmtStub->method('execute')->willReturn(true);
        $stmtStub->method('fetchAll')->willReturn($mockData);

        $pdoStub = $this->createStub(PDO::class);
        $pdoStub->method('prepare')->willReturn($stmtStub);
        
        $model = new BukuModel($pdoStub);
        $result = $model->getAllBuku('PHP Dasar');
        
        $this->assertCount(1, $result);
        $this->assertEquals('PHP Dasar', $result[0]['judul']);
    }

    public function testSearchBukuByPenulis(): void {
        $mockData = [
            ['id_buku' => 'BK-002', 'judul' => 'Laravel Dasar', 'penulis' => 'Dimas']
        ];
        
        $stmtStub = $this->createStub(PDOStatement::class);
        $stmtStub->method('execute')->willReturn(true);
        $stmtStub->method('fetchAll')->willReturn($mockData);

        $pdoStub = $this->createStub(PDO::class);
        $pdoStub->method('prepare')->willReturn($stmtStub);
        
        $model = new BukuModel($pdoStub);
        $result = $model->getAllBuku('Dimas');
        
        $this->assertCount(1, $result);
        $this->assertEquals('Dimas', $result[0]['penulis']);
    }

    public function testSearchBukuReturnsEmptyOnNoMatch(): void {
        $stmtStub = $this->createStub(PDOStatement::class);
        $stmtStub->method('execute')->willReturn(true);
        $stmtStub->method('fetchAll')->willReturn([]);

        $pdoStub = $this->createStub(PDO::class);
        $pdoStub->method('prepare')->willReturn($stmtStub);
        
        $model = new BukuModel($pdoStub);
        $result = $model->getAllBuku('Unknown');
        
        $this->assertEmpty($result);
    }

    public function testGetAllBukuReturnsAllBooks(): void {
        $mockData = [
            ['id_buku' => 'BK-001', 'judul' => 'A', 'stok' => 5],
            ['id_buku' => 'BK-002', 'judul' => 'B', 'stok' => 0]
        ];
        $bukuModel = new BukuModel($this->createPdoStub($mockData));
        $result = $bukuModel->getAllBuku();
        $this->assertCount(2, $result);
    }

    public function testCreateBuku(): void {
        $bukuModel = new BukuModel($this->createPdoStub());
        $result = $bukuModel->create([
            'id_buku' => 'BK-999', 'id_kategori' => 1, 'judul' => 'Buku Baru',
            'penulis' => 'X', 'penerbit' => 'X', 'stok' => 10
        ]);
        $this->assertTrue($result);
    }

    public function testUpdateBuku(): void {
        $bukuModel = new BukuModel($this->createPdoStub());
        $result = $bukuModel->update('BK-001', [
            'id_kategori' => 1, 'judul' => 'Judul Diperbarui',
            'penulis' => 'Andi', 'penerbit' => 'Info', 'stok' => 99
        ]);
        $this->assertTrue($result);
    }

    public function testDeleteBuku(): void {
        $bukuModel = new BukuModel($this->createPdoStub());
        $result = $bukuModel->delete('BK-001');
        $this->assertTrue($result);
    }

    public function testFixStokNegatif(): void {
        $bukuModel = new BukuModel($this->createPdoStub());
        $result = $bukuModel->fixStokNegatif();
        $this->assertNotFalse($result);
    }
}
