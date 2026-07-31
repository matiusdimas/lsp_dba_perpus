<?php

require_once __DIR__ . '/SeederInterface.php';

class DatabaseSeeder {
    private $db;
    private $seedersPath;

    public function __construct(PDO $db, $seedersPath) {
        $this->db = $db;
        $this->seedersPath = $seedersPath;
    }

    public function seed() {
        echo "Menjalankan seeder...\n";
        
        // Urutan penting karena foreign keys
        $seederOrder = [
            'KategoriSeeder.php',
            'BukuSeeder.php',
            'AnggotaSeeder.php',
            'PeminjamanSeeder.php',
            'PengembalianSeeder.php',
            'DokumenSeeder.php',
            'UserSeeder.php'
        ];

        $this->db->exec('SET FOREIGN_KEY_CHECKS = 0');

        foreach ($seederOrder as $file) {
            $path = $this->seedersPath . '/' . $file;
            if (file_exists($path)) {
                require_once $path;
                $className = basename($file, '.php');
                if (class_exists($className)) {
                    $seeder = new $className();
                    if ($seeder instanceof SeederInterface) {
                        echo "Seeding: " . $file . "\n";
                        try {
                            $seeder->run($this->db);
                            echo "Sukses: " . $file . "\n";
                        } catch (PDOException $e) {
                            echo "Gagal: " . $file . " - " . $e->getMessage() . "\n";
                        }
                    }
                }
            }
        }
        
        $this->db->exec('SET FOREIGN_KEY_CHECKS = 1');
        echo "Seeding selesai.\n";
    }
}
