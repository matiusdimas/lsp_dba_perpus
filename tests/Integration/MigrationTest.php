<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../app/Core/Database/MigrationInterface.php';
require_once __DIR__ . '/../../app/Core/Database/Migrator.php';

class MigrationTest extends TestCase {

    public function testMigratorRunsAllMigrationFiles(): void {
        $pdoStub  = $this->createStub(PDO::class);
        $stmtStub = $this->createStub(PDOStatement::class);
        $stmtStub->method('fetchColumn')->willReturn('test_db');
        $pdoStub->method('query')->willReturn($stmtStub);
        $pdoStub->method('exec')->willReturn(1);

        $migrationsPath = __DIR__ . '/../../database/migrations';

        ob_start();
        $migrator = new Migrator($pdoStub, $migrationsPath);
        $migrator->migrate();
        $output = ob_get_clean();

        $this->assertStringContainsString('Migrasi selesai', $output);
        $this->assertStringContainsString('001_create_kategori_table', $output);
        $this->assertStringContainsString('002_create_buku_table', $output);
        $this->assertStringContainsString('003_create_anggota_table', $output);
        $this->assertStringContainsString('004_create_peminjaman_table', $output);
        $this->assertStringContainsString('005_create_pengembalian_table', $output);
        $this->assertStringContainsString('006_create_dokumen_table', $output);
        $this->assertStringContainsString('007_create_users_table', $output);
    }

    public function testMigratorRollbackRunsInReverseOrder(): void {
        $pdoStub  = $this->createStub(PDO::class);
        $stmtStub = $this->createStub(PDOStatement::class);
        $stmtStub->method('fetchColumn')->willReturn('test_db');
        $pdoStub->method('query')->willReturn($stmtStub);
        $pdoStub->method('exec')->willReturn(1);

        $migrationsPath = __DIR__ . '/../../database/migrations';

        ob_start();
        $migrator = new Migrator($pdoStub, $migrationsPath);
        $migrator->rollback();
        $output = ob_get_clean();

        $this->assertStringContainsString('Rollback selesai', $output);
        $pos007 = strpos($output, '007_create_users_table');
        $pos001 = strpos($output, '001_create_kategori_table');
        $this->assertLessThan($pos001, $pos007, "007 harus di-rollback SEBELUM 001.");
    }
}
