<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

$root = dirname(__DIR__);
$env  = $root.'/.env';

if (class_exists(Dotenv::class) && file_exists($env)) {
    (new Dotenv())->usePutenv()->loadEnv($env, 'APP_ENV', 'dev', ['test']);
}

$keys = [
    'FORTIS_RUN_LIVE',
    'FORTIS_DEVELOPER_ID',
    'FORTIS_USER_ID',
    'FORTIS_USER_API_KEY',
    'FORTIS_LOCATION_ID',
    'FORTIS_SANDBOX',
    'FORTIS_TEST_TOKEN',
    'FORTIS_TEST_CC',
    'FORTIS_TEST_EXP',
    'FORTIS_TEST_CVV',
];

foreach ($keys as $k) {
    $v = getenv($k);
    echo $k, '=', ($v === false ? 'NOT SET' : ($k === 'FORTIS_USER_API_KEY' ? '[hidden]' : $v)), PHP_EOL;
}
