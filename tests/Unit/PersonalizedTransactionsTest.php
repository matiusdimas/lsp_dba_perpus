<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../app/Core/Model.php';
require_once __DIR__ . '/../../app/Models/PeminjamanModel.php';
require_once __DIR__ . '/../../app/Models/PengembalianModel.php';

class PersonalizedTransactionsTest extends TestCase {

    public function testGetPeminjamanAktifFiltersByAnggotaIdWhenProvided(): void {
        $dummyData = [[
            'id_peminjaman'   => 'PJ-2026-001',
            'nama_peminjam'   => 'Dimas Prasetia',
            'judul_buku'      => 'Clean Code',
            'tgl_pinjam'      => '2026-08-01',
            'tgl_jatuh_tempo' => '2026-08-08',
            'status'          => 'Dipinjam'
        ]];

        $stmtStub = $this->createStub(PDOStatement::class);
        $stmtStub->method('execute')->willReturn(true);
        $stmtStub->method('fetchAll')->willReturn($dummyData);

        $pdoStub = $this->createStub(PDO::class);
        $pdoStub->method('prepare')->willReturn($stmtStub);

        $model = new PeminjamanModel($pdoStub);
        $result = $model->getPeminjamanAktif('AG-001');

        $this->assertCount(1, $result);
        $this->assertEquals('Dimas Prasetia', $result[0]['nama_peminjam']);
    }

    public function testGetAllPengembalianFiltersByAnggotaIdWhenProvided(): void {
        $dummyData = [[
            'id_pengembalian' => 'KB-2026-001',
            'id_peminjaman'   => 'PJ-2026-001',
            'nama_peminjam'   => 'Dimas Prasetia',
            'judul'           => 'Clean Code',
            'tgl_kembali'     => '2026-08-08',
            'denda'           => 0
        ]];

        $stmtStub = $this->createStub(PDOStatement::class);
        $stmtStub->method('execute')->willReturn(true);
        $stmtStub->method('fetchAll')->willReturn($dummyData);

        $pdoStub = $this->createStub(PDO::class);
        $pdoStub->method('prepare')->willReturn($stmtStub);

        $model = new PengembalianModel($pdoStub);
        $result = $model->getAllPengembalian('AG-001');

        $this->assertCount(1, $result);
        $this->assertEquals('Dimas Prasetia', $result[0]['nama_peminjam']);
    }
}
