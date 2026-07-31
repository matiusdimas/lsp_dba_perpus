<?php

require_once __DIR__ . '/MigrationInterface.php';

class Migrator {
    private $db;
    private $migrationsPath;

    public function __construct(PDO $db, $migrationsPath) {
        $this->db = $db;
        $this->migrationsPath = $migrationsPath;
    }

    public function migrate() {
        echo "Menjalankan migrasi...\n";
        $files = glob($this->migrationsPath . '/*.php');
        sort($files);

        $dbName = $this->db->query('select database()')->fetchColumn();
        
        // Nonaktifkan foreign key checks selama migrasi (buat struktur ulang)
        $this->db->exec('SET FOREIGN_KEY_CHECKS = 0');

        foreach ($files as $file) {
            require_once $file;
            $className = $this->getClassName($file);
            if (class_exists($className)) {
                $migration = new $className();
                if ($migration instanceof MigrationInterface) {
                    echo "Migrating: " . basename($file) . "\n";
                    try {
                        $migration->up($this->db);
                        echo "Sukses: " . basename($file) . "\n";
                    } catch (PDOException $e) {
                        echo "Gagal: " . basename($file) . " - " . $e->getMessage() . "\n";
                    }
                }
            }
        }
        
        $this->db->exec('SET FOREIGN_KEY_CHECKS = 1');
        echo "Migrasi selesai.\n";
    }
    
    public function rollback() {
        echo "Rollback migrasi...\n";
        $files = glob($this->migrationsPath . '/*.php');
        rsort($files); // Reverse sort for rollback

        $this->db->exec('SET FOREIGN_KEY_CHECKS = 0');

        foreach ($files as $file) {
            require_once $file;
            $className = $this->getClassName($file);
            if (class_exists($className)) {
                $migration = new $className();
                if ($migration instanceof MigrationInterface) {
                    echo "Rolling back: " . basename($file) . "\n";
                    try {
                        $migration->down($this->db);
                        echo "Sukses: " . basename($file) . "\n";
                    } catch (PDOException $e) {
                        echo "Gagal: " . basename($file) . " - " . $e->getMessage() . "\n";
                    }
                }
            }
        }
        
        $this->db->exec('SET FOREIGN_KEY_CHECKS = 1');
        echo "Rollback selesai.\n";
    }

    private function getClassName($file) {
        // Asumsi nama file: 001_create_kategori_table.php -> CreateKategoriTable
        $filename = basename($file, '.php');
        $parts = explode('_', $filename);
        array_shift($parts); // Hapus angka (001)
        $className = '';
        foreach ($parts as $part) {
            $className .= ucfirst($part);
        }
        return $className;
    }
}
