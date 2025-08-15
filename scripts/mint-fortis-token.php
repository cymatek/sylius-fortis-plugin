<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

$root = dirname(__DIR__);
if (class_exists(Dotenv::class) && is_file("$root/.env")) {
    (new Dotenv())->usePutenv()->loadEnv("$root/.env", 'APP_ENV', 'dev', ['test']);
}

$host   = getenv('FORTIS_TECH_HOST') ?: 'https://api.sandbox.fortis.tech'; // v1 Tech host
$devId  = getenv('FORTIS_DEVELOPER_ID') ?: '';
$userId = getenv('FORTIS_USER_ID') ?: '';
$apiKey = getenv('FORTIS_USER_API_KEY') ?: '';
$loc    = getenv('FORTIS_LOCATION_ID') ?: '';
$cc     = getenv('FORTIS_TEST_CC') ?: '';
$exp    = getenv('FORTIS_TEST_EXP') ?: ''; // MMYY

if (!$devId || !$userId || !$apiKey || !$loc || !$cc || !$exp) {
    fwrite(STDERR, "Missing env vars. Need FORTIS_DEVELOPER_ID, FORTIS_USER_ID, FORTIS_USER_API_KEY, FORTIS_LOCATION_ID, FORTIS_TEST_CC, FORTIS_TEST_EXP\n");
    exit(1);
}

// v1 token creation: top-level fields only (NO payment_method, NO cvv)
$payload = [
    'location_id'         => $loc,
    'account_number'      => $cc,
    'exp_date'            => $exp,   // MMYY
    'account_holder_name' => 'CI Test',
];

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL            => rtrim($host, '/') . '/v1/tokens/cc',
    CURLOPT_CUSTOMREQUEST  => 'POST',
    CURLOPT_HTTPHEADER     => [
        'content-type: application/json',
        'accept: application/json',
        "developer-id: $devId",
        "user-id: $userId",
        "user-api-key: $apiKey",
    ],
    CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_SLASHES),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 30,
]);

$resp = curl_exec($ch);
$err  = curl_error($ch);
$code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
curl_close($ch);

if ($err) { fwrite(STDERR, "cURL Error: $err\n"); exit(2); }

$data   = json_decode((string)$resp, true) ?: [];
$token  = $data['data']['id']             // ✅ common shape
    ?? $data['token']['id']            // alt shape
    ?? null;

if ($code < 200 || $code >= 300 || !$token) {
    fwrite(STDERR, "Unexpected response ($code): $resp\n");
    exit(3);
}

echo $token, PHP_EOL;
