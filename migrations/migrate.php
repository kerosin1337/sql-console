<?php
require_once './src/Database.php';

/**
 * Скрипт для выполнения миграций базы данных
 * Запуск: php migrations/migrate.php
 */

$configFile = __DIR__ . '/../config/config.ini';
var_dump($configFile);
if (!file_exists($configFile)) {
    die("Configuration file not found: $configFile\n");
}

$config = parse_ini_file($configFile, true);
if (!$config) {
    die("Invalid configuration file\n");
}

try {
    $db = $config['database'];


    $pdo = Database::getConnection();


    $dbName = $db['dbname'];
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✓ Database '$dbName' created or already exists\n";

    $pdo->exec("USE `$dbName`");

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage() . "\n");
}

$pdo->exec("
    CREATE TABLE IF NOT EXISTS migrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        migration VARCHAR(255) NOT NULL,
        batch INT NOT NULL,
        executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB
");

$executed = $pdo->query("SELECT migration FROM migrations")->fetchAll(PDO::FETCH_COLUMN);

$currentBatch = $pdo->query("SELECT COALESCE(MAX(batch), 0) FROM migrations")->fetchColumn();
$newBatch = $currentBatch + 1;

$migrationFiles = glob(__DIR__ . '/*.sql');
natsort($migrationFiles);


$executedCount = 0;
$pendingCount = 0;

echo "\nStarting migrations...\n";
echo "========================\n\n";

foreach ($migrationFiles as $file) {
    $migrationName = basename($file);

    if (in_array($migrationName, $executed)) {
        echo "⏭ Skipping (already executed): $migrationName\n";
        continue;
    }

    try {
        echo "▶ Executing: $migrationName... ";

        $sql = file_get_contents($file);

        $queries = array_filter(array_map('trim', explode(';', $sql)));

        foreach ($queries as $query) {
            if (!empty($query)) {
                $pdo->exec($query);
            }
        }

        $stmt = $pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)");
        $stmt->execute([$migrationName, $newBatch]);

        echo "✓ DONE\n";
        $executedCount++;

    } catch (PDOException $e) {
        echo "✗ FAILED\n";
        echo "Error: " . $e->getMessage() . "\n";
        exit(1);
    }
}

echo "\n========================\n";
echo "Migration completed!\n";
echo "Executed: $executedCount new migration(s)\n";
echo "Batch: $newBatch\n";