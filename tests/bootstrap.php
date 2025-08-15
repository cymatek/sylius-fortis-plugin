<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

$projectRoot = \dirname(__DIR__);
$envFile = $projectRoot . '/.env';

// Default to test if not set
$_SERVER['APP_ENV'] = $_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? 'test';

if (class_exists(Dotenv::class) && file_exists($envFile)) {
    $dotenv = new Dotenv();
    // Load chain: .env, .env.local, .env.test, .env.test.local
    $dotenv->usePutenv()->loadEnv($envFile, 'APP_ENV', 'dev', ['test']);

    // 🔧 Ensure .env.local wins for this PHP process (so getenv() sees FORTIS_TEST_TOKEN)
    $local = $projectRoot . '/.env.local';
    if (is_file($local)) {
        (new Dotenv())->usePutenv()->overload($local);
    }

    // (optional) also overload .env.test.local if you use it
    $testLocal = $projectRoot . '/.env.test.local';
    if (is_file($testLocal)) {
        (new Dotenv())->usePutenv()->overload($testLocal);
    }
}
