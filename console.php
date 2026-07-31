<?php

require_once __DIR__ . '/app/Config/Database.php';
require_once __DIR__ . '/app/Core/Database/Migrator.php';
require_once __DIR__ . '/app/Core/Database/DatabaseSeeder.php';

// Memastikan dipanggil dari CLI
if (php_sapi_name() !== 'cli') {
    die("Script ini hanya dapat dijalankan melalui CLI.");
}

$db = Database::getInstance();

$migrationsPath = __DIR__ . '/database/migrations';
$seedersPath = __DIR__ . '/database/seeders';

$command = $argv[1] ?? null;

if ($command === 'migrate') {
    $migrator = new Migrator($db, $migrationsPath);
    $migrator->migrate();
} elseif ($command === 'migrate:rollback') {
    $migrator = new Migrator($db, $migrationsPath);
    $migrator->rollback();
} elseif ($command === 'seed') {
    $seeder = new DatabaseSeeder($db, $seedersPath);
    $seeder->seed();
} elseif ($command === 'migrate:fresh') {
    $migrator = new Migrator($db, $migrationsPath);
    $migrator->rollback();
    echo "\n";
    $migrator->migrate();
    echo "\n";
    $seeder = new DatabaseSeeder($db, $seedersPath);
    $seeder->seed();
} else {
    echo "Perintah tidak dikenali.\n";
    echo "Gunakan: \n";
    echo "  php console.php migrate\n";
    echo "  php console.php migrate:rollback\n";
    echo "  php console.php seed\n";
    echo "  php console.php migrate:fresh (rollback + migrate + seed)\n";
}
