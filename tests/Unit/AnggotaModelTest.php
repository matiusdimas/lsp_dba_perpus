<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../app/Core/Model.php';
require_once __DIR__ . '/../../app/Models/AnggotaModel.php';

class AnggotaModelTest extends TestCase {

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

    public function testGetAllAnggota(): void {
        $data = [['id_anggota' => 'AG-001', 'nama' => 'Dimas']];
        $model = new AnggotaModel($this->createPdoStub($data));
        $this->assertCount(1, $model->getAllAnggota());
    }

    public function testSearchAnggota(): void {
        $mockData = [
            ['id_anggota' => 'AG-002', 'nama' => 'Akbar', 'email' => 'akbar@gmail.com'],
            ['id_anggota' => 'AG-003', 'nama' => 'Akhyar', 'email' => 'akhyar@gmail.com']
        ];
        
        $stmtStub = $this->createStub(PDOStatement::class);
        $stmtStub->method('execute')->willReturn(true);
        $stmtStub->method('fetchAll')->willReturn($mockData);
        
        $pdoStub = $this->createStub(PDO::class);
        $pdoStub->method('prepare')->willReturn($stmtStub);
        
        $model = new AnggotaModel($pdoStub);
        $result = $model->getAllAnggota('Ak');
        
        $this->assertCount(2, $result);
        $this->assertEquals('Akbar', $result[0]['nama']);
    }

    public function testCreateAnggota(): void {
        $model = new AnggotaModel($this->createPdoStub());
        $result = $model->create([
            'id_anggota' => 'AG-099', 'nama' => 'Test User',
            'email' => 'test@test.com', 'no_hp' => '0811', 'alamat' => 'Bandung'
        ]);
        $this->assertTrue($result);
    }

    public function testUpdateAnggota(): void {
        $model = new AnggotaModel($this->createPdoStub());
        $result = $model->update('AG-004', [
            'nama' => 'Yana', 'email' => 'yana@gmail.com',
            'no_hp' => '0833', 'alamat' => 'Surabaya'
        ]);
        $this->assertTrue($result);
    }

    public function testDeleteAnggota(): void {
        $model = new AnggotaModel($this->createPdoStub());
        $this->assertTrue($model->delete('AG-004'));
    }

    public function testCheckDataGanda(): void {
        $data = [['email' => 'ganda@gmail.com', 'jumlah' => 2]];
        $model = new AnggotaModel($this->createPdoStub($data));
        $result = $model->checkDataGanda();
        $this->assertCount(1, $result);
        $this->assertEquals('ganda@gmail.com', $result[0]['email']);
    }

    public function testCheckDataInvalid(): void {
        $data = [['id_anggota' => 'AG-003', 'nama' => '', 'email' => 'bademail']];
        $model = new AnggotaModel($this->createPdoStub($data));
        $this->assertCount(1, $model->checkDataInvalid());
    }

    public function testImportCSVFromFile(): void {
        $csvContent = "nama,email,no_hp,alamat\nImport Test,import@test.com,0815,Surabaya";
        $tmpFile = tempnam(sys_get_temp_dir(), 'csv');
        file_put_contents($tmpFile, $csvContent);

        $model = new AnggotaModel($this->createPdoStub());
        $count = $model->importCSV($tmpFile);
        unlink($tmpFile);

        $this->assertEquals(1, $count);
    }

    public function testImportCSVReturnsFalseForMissingFile(): void {
        $model = new AnggotaModel($this->createPdoStub());
        $this->assertFalse($model->importCSV('/invalid/path.csv'));
    }
}
