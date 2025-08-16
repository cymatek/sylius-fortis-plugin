<?php
declare(strict_types=1);

namespace Vendor\FortisPlugin\Api;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;

// Make SDK classes optional: only referenced when they exist
// (do not remove these use statements; they don't error if the classes aren't installed)
use FortisAPILib\Environment;
use FortisAPILib\Exceptions\ApiException;
use FortisAPILib\FortisAPIClient;
use FortisAPILib\FortisAPIClientBuilder;
use FortisAPILib\Models\V1TransactionsCcSaleKeyedRequest;
use FortisAPILib\Models\V1TransactionsCcSaleTokenRequest;
use FortisAPILib\Models\V1TransactionsCcAuthOnlyKeyedRequest;
use FortisAPILib\Models\V1TransactionsCcAuthOnlyTokenRequest;
use FortisAPILib\Models\V1TransactionsAuthCompleteRequest;
use Core\Exceptions\AuthValidationException;

final class FortisSdkAdapter implements FortisApiInterface
{
    /** @var FortisAPIClient|null */
    private ?FortisAPIClient $client = null;

    private string $baseUrl;
    private HttpClient $http;

    public function __construct(
        private readonly string $developerId,
        private readonly string $userId,
        private readonly string $userApiKey,
        private readonly ?string $locationId = null,
        private readonly bool $sandbox = true,
        private readonly int $timeout = 30,
    ) {
        $this->baseUrl = $this->sandbox
            ? 'https://api.sandbox.fortis.tech'
            : 'https://api.fortis.tech';

        // Always have HTTP fallback
        $this->http = new HttpClient([
            'base_uri' => $this->baseUrl . '/',
            'timeout'  => $this->timeout,
        ]);

        // Build SDK client only if the package is installed
        if (class_exists(\FortisAPILib\FortisAPIClientBuilder::class)) {
            $builder = FortisAPIClientBuilder::init()
                ->environment($this->sandbox ? Environment::SANDBOX : Environment::PRODUCTION);

            // Try assigning creds; ignore if methods/types differ per build
            foreach ([
                         ['userIdCredentials', $this->userId],
                         ['userApiKeyCredentials', $this->userApiKey],
                         ['developerIdCredentials', $this->developerId],
                     ] as [$method, $value]) {
                if (method_exists($builder, $method)) {
                    try { $builder = $builder->$method($value); } catch (\Throwable) {}
                }
            }

            if (method_exists($builder, 'additionalHeaders')) {
                $builder = $builder->additionalHeaders([
                    'developer-id' => $this->developerId,
                    'user-id'      => $this->userId,
                    'user-api-key' => $this->userApiKey,
                    'content-type' => 'application/json',
                ]);
            } elseif (method_exists($builder, 'globalHeaders')) {
                $builder = $builder->globalHeaders([
                    'developer-id' => $this->developerId,
                    'user-id'      => $this->userId,
                    'user-api-key' => $this->userApiKey,
                    'content-type' => 'application/json',
                ]);
            }

            if (method_exists($builder, 'httpClientOptions')) {
                $builder = $builder->httpClientOptions(['timeout' => $this->timeout]);
            }

            try {
                $this->client = $builder->build();
            } catch (\Throwable) {
                $this->client = null; // fall back to HTTP
            }
        }
    }

    private function shouldStrict(): bool
    {
        return ((string) (getenv('FORTIS_SDK_STRICT') ?: '0')) === '1';
    }

    /** @param mixed $body */
    private function toArray(mixed $body): array
    {
        return json_decode(json_encode($body, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);
    }

    /** @return array{errors: array<string, array<int, string>>, code?: int} */
    private function errorArray(ApiException|\Throwable $e): array
    {
        $raw = ($e instanceof ApiException)
        && $e->isResponseAvailable()
        && $e->getHttpResponse()
        && method_exists($e->getHttpResponse(), 'getRawBody')
            ? $e->getHttpResponse()->getRawBody()
            : null;

        $decoded = is_string($raw) ? json_decode($raw, true) : null;

        return [
            'errors' => $decoded['errors'] ?? ['api' => [trim($e->getMessage())]],
            'code'   => (int) $e->getCode(),
        ];
    }

    /** @param array<string,mixed> $json @return array<string,mixed> */
    private function fallbackPostJson(string $path, array $json): array
    {
        $json = array_filter($json, static fn($v) => $v !== null);

        try {
            $resp = $this->http->request('POST', ltrim($path, '/'), [
                'headers' => [
                    'content-type' => 'application/json',
                    'accept'       => 'application/json',
                    'developer-id' => $this->developerId,
                    'user-id'      => $this->userId,
                    'user-api-key' => $this->userApiKey,
                ],
                'json' => $json,
            ]);
            /** @var array<string,mixed> $decoded */
            $decoded = json_decode((string) $resp->getBody(), true, 512, JSON_THROW_ON_ERROR);
            return $decoded;
        } catch (GuzzleException|\Throwable $e) {
            return [
                'errors' => ['http' => [trim($e->getMessage())]],
                'code'   => method_exists($e, 'getCode') ? (int) $e->getCode() : 0,
            ];
        }
    }

    /** @return array<string,mixed> */
    public function saleTokenized(string $tokenId, int $amountMinor, ?string $orderNumber = null, ?string $locationId = null): array
    {
        // If SDK missing, go straight to HTTP
        if (!$this->client) {
            return $this->fallbackPostJson('v1/transactions/cc/sale/token', [
                'token_id'           => $tokenId,
                'transaction_amount' => $amountMinor,
                'order_number'       => $orderNumber,
                'location_id'        => $locationId ?? $this->locationId,
            ]);
        }

        try {
            try { $req = new V1TransactionsCcSaleTokenRequest($amountMinor, $tokenId); }
            catch (\Throwable) { $req = new V1TransactionsCcSaleTokenRequest($tokenId, $amountMinor); }

            if ($orderNumber !== null && method_exists($req, 'setOrderNumber')) { $req->setOrderNumber($orderNumber); }
            if (($locationId ?? $this->locationId) !== null && method_exists($req, 'setLocationId')) { $req->setLocationId($locationId ?? $this->locationId); }

            $res = $this->client->getTransactionsCreditCardController()->cCSaleTokenized($req);
            return $this->toArray($res);
        } catch (AuthValidationException $e) {
            if ($this->shouldStrict()) { throw $e; }
            return $this->fallbackPostJson('v1/transactions/cc/sale/token', [
                'token_id'           => $tokenId,
                'transaction_amount' => $amountMinor,
                'order_number'       => $orderNumber,
                'location_id'        => $locationId ?? $this->locationId,
            ]);
        } catch (ApiException $e) {
            return $this->errorArray($e);
        }
    }

    /** @return array<string,mixed> */
    public function saleKeyed(string $pan, string $expMMYY, ?string $cvv, int $amountMinor, ?string $orderNumber = null, ?string $locationId = null): array
    {
        if (!$this->client) {
            return $this->fallbackPostJson('v1/transactions/cc/sale/keyed', [
                'account_number'     => $pan,
                'exp_date'           => $expMMYY,
                'cvv'                => $cvv,
                'transaction_amount' => $amountMinor,
                'order_number'       => $orderNumber,
                'location_id'        => $locationId ?? $this->locationId,
            ]);
        }

        try {
            try { $req = new V1TransactionsCcSaleKeyedRequest($amountMinor, $pan, $expMMYY); }
            catch (\Throwable) { $req = new V1TransactionsCcSaleKeyedRequest($pan, $expMMYY, $amountMinor); }

            if ($cvv !== null && method_exists($req, 'setCvv')) { $req->setCvv($cvv); }
            if ($orderNumber !== null && method_exists($req, 'setOrderNumber')) { $req->setOrderNumber($orderNumber); }
            if (($locationId ?? $this->locationId) !== null && method_exists($req, 'setLocationId')) { $req->setLocationId($locationId ?? $this->locationId); }

            $res = $this->client->getTransactionsCreditCardController()->cCSale($req);
            return $this->toArray($res);
        } catch (AuthValidationException $e) {
            if ($this->shouldStrict()) { throw $e; }
            return $this->fallbackPostJson('v1/transactions/cc/sale/keyed', [
                'account_number'     => $pan,
                'exp_date'           => $expMMYY,
                'cvv'                => $cvv,
                'transaction_amount' => $amountMinor,
                'order_number'       => $orderNumber,
                'location_id'        => $locationId ?? $this->locationId,
            ]);
        } catch (ApiException $e) {
            return $this->errorArray($e);
        }
    }

    /** @return array<string,mixed> */
    public function authTokenized(string $tokenId, int $amountMinor, ?string $orderNumber = null, ?string $locationId = null): array
    {
        if (!$this->client) {
            return $this->fallbackPostJson('v1/transactions/cc/auth-only/token', [
                'token_id'           => $tokenId,
                'transaction_amount' => $amountMinor,
                'order_number'       => $orderNumber,
                'location_id'        => $locationId ?? $this->locationId,
            ]);
        }

        try {
            try { $req = new V1TransactionsCcAuthOnlyTokenRequest($amountMinor, $tokenId); }
            catch (\Throwable) { $req = new V1TransactionsCcAuthOnlyTokenRequest($tokenId, $amountMinor); }

            if ($orderNumber !== null && method_exists($req, 'setOrderNumber')) { $req->setOrderNumber($orderNumber); }
            if (($locationId ?? $this->locationId) !== null && method_exists($req, 'setLocationId')) { $req->setLocationId($locationId ?? $this->locationId); }

            $res = $this->client->getTransactionsCreditCardController()->cCAuthOnlyTokenized($req);
            return $this->toArray($res);
        } catch (AuthValidationException $e) {
            if ($this->shouldStrict()) { throw $e; }
            return $this->fallbackPostJson('v1/transactions/cc/auth-only/token', [
                'token_id'           => $tokenId,
                'transaction_amount' => $amountMinor,
                'order_number'       => $orderNumber,
                'location_id'        => $locationId ?? $this->locationId,
            ]);
        } catch (ApiException $e) {
            return $this->errorArray($e);
        }
    }

    /** @return array<string,mixed> */
    public function authKeyed(string $pan, string $expMMYY, ?string $cvv, int $amountMinor, ?string $orderNumber = null, ?string $locationId = null): array
    {
        if (!$this->client) {
            return $this->fallbackPostJson('v1/transactions/cc/auth-only/keyed', [
                'account_number'     => $pan,
                'exp_date'           => $expMMYY,
                'cvv'                => $cvv,
                'transaction_amount' => $amountMinor,
                'order_number'       => $orderNumber,
                'location_id'        => $locationId ?? $this->locationId,
            ]);
        }

        try {
            try { $req = new V1TransactionsCcAuthOnlyKeyedRequest($amountMinor, $pan, $expMMYY); }
            catch (\Throwable) { $req = new V1TransactionsCcAuthOnlyKeyedRequest($pan, $expMMYY, $amountMinor); }

            if ($cvv !== null && method_exists($req, 'setCvv')) { $req->setCvv($cvv); }
            if ($orderNumber !== null && method_exists($req, 'setOrderNumber')) { $req->setOrderNumber($orderNumber); }
            if (($locationId ?? $this->locationId) !== null && method_exists($req, 'setLocationId')) { $req->setLocationId($locationId ?? $this->locationId); }

            $res = $this->client->getTransactionsCreditCardController()->cCAuthOnly($req);
            return $this->toArray($res);
        } catch (AuthValidationException $e) {
            if ($this->shouldStrict()) { throw $e; }
            return $this->fallbackPostJson('v1/transactions/cc/auth-only/keyed', [
                'account_number'     => $pan,
                'exp_date'           => $expMMYY,
                'cvv'                => $cvv,
                'transaction_amount' => $amountMinor,
                'order_number'       => $orderNumber,
                'location_id'        => $locationId ?? $this->locationId,
            ]);
        } catch (ApiException $e) {
            return $this->errorArray($e);
        }
    }

    /** @return array<string,mixed> */
    public function capture(string $transactionId, int $amountMinor): array
    {
        if (!$this->client) {
            return $this->fallbackPostJson("v1/transactions/{$transactionId}/auth-complete", [
                'transaction_amount' => $amountMinor
            ]);
        }

        try {
            try { $req = new V1TransactionsAuthCompleteRequest($amountMinor); }
            catch (\Throwable) {
                $req = new V1TransactionsAuthCompleteRequest();
                if (method_exists($req, 'setTransactionAmount')) { $req->setTransactionAmount($amountMinor); }
            }
            $res = $this->client->getTransactionsUpdatesController()->authComplete($transactionId, $req);
            return $this->toArray($res);
        } catch (AuthValidationException $e) {
            if ($this->shouldStrict()) { throw $e; }
            return $this->fallbackPostJson("v1/transactions/{$transactionId}/auth-complete", [
                'transaction_amount' => $amountMinor
            ]);
        } catch (ApiException $e) {
            return $this->errorArray($e);
        }
    }

    /** @return array<string,mixed> */
    public function void(string $transactionId): array
    {
        if (!$this->client) {
            return $this->fallbackPostJson("v1/transactions/{$transactionId}/void", []);
        }

        try {
            $res = $this->client->getTransactionsUpdatesController()->void($transactionId);
            return $this->toArray($res);
        } catch (AuthValidationException $e) {
            if ($this->shouldStrict()) { throw $e; }
            return $this->fallbackPostJson("v1/transactions/{$transactionId}/void", []);
        } catch (ApiException $e) {
            return $this->errorArray($e);
        }
    }

    /** @return array<string,mixed> */
    public function refund(string $transactionId, int $amountMinor): array
    {
        if (!$this->client) {
            return $this->fallbackPostJson("v1/transactions/{$transactionId}/refund", [
                'transaction_amount' => $amountMinor
            ]);
        }

        try {
            if (class_exists('\\FortisAPILib\\Models\\V1TransactionsRefundRequest')) {
                $cls = '\\FortisAPILib\\Models\\V1TransactionsRefundRequest';
                try { $req = new $cls($amountMinor); }
                catch (\Throwable) {
                    $req = new $cls();
                    if (method_exists($req, 'setTransactionAmount')) { $req->setTransactionAmount($amountMinor); }
                }
                $res = $this->client->getTransactionsUpdatesController()->refundTransaction($transactionId, $req);
            } else {
                $res = $this->client->getTransactionsUpdatesController()->refundTransaction(
                    $transactionId,
                    ['transaction_amount' => $amountMinor]
                );
            }
            return $this->toArray($res);
        } catch (AuthValidationException $e) {
            if ($this->shouldStrict()) { throw $e; }
            return $this->fallbackPostJson("v1/transactions/{$transactionId}/refund", [
                'transaction_amount' => $amountMinor
            ]);
        } catch (ApiException $e) {
            return $this->errorArray($e);
        }
    }

    /** @return array<string,mixed> */
    public function get(string $transactionId): array
    {
        // Read via SDK only if present; otherwise you can add a GET fallback later if needed.
        if ($this->client) {
            try {
                $res = $this->client->getTransactionsReadController()->getTransaction($transactionId);
                return $this->toArray($res);
            } catch (ApiException $e) {
                return $this->errorArray($e);
            }
        }

        // Minimal HTTP GET fallback example:
        try {
            $resp = $this->http->request('GET', 'v1/transactions/' . rawurlencode($transactionId), [
                'headers' => [
                    'accept'       => 'application/json',
                    'developer-id' => $this->developerId,
                    'user-id'      => $this->userId,
                    'user-api-key' => $this->userApiKey,
                ]
            ]);
            /** @var array<string,mixed> $decoded */
            $decoded = json_decode((string) $resp->getBody(), true, 512, JSON_THROW_ON_ERROR);
            return $decoded;
        } catch (GuzzleException|\Throwable $e) {
            return [
                'errors' => ['http' => [trim($e->getMessage())]],
                'code'   => method_exists($e, 'getCode') ? (int) $e->getCode() : 0,
            ];
        }
    }
}
