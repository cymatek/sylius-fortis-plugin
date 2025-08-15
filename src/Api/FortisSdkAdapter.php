<?php
declare(strict_types=1);

namespace Vendor\FortisPlugin\Api;

use FortisAPILib\Environment;
use FortisAPILib\Exceptions\ApiException;
use FortisAPILib\FortisAPIClient;
use FortisAPILib\FortisAPIClientBuilder;

// Typed request models
use FortisAPILib\Models\V1TransactionsCcSaleKeyedRequest;
use FortisAPILib\Models\V1TransactionsCcSaleTokenRequest;
use FortisAPILib\Models\V1TransactionsCcAuthOnlyKeyedRequest;
use FortisAPILib\Models\V1TransactionsCcAuthOnlyTokenRequest;
use FortisAPILib\Models\V1TransactionsAuthCompleteRequest;

// APIMatic auth-layer exception (thrown before any HTTP call)
use Core\Exceptions\AuthValidationException;

// Fallback HTTP
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;

final class FortisSdkAdapter implements FortisApiInterface
{
    private FortisAPIClient $client;
    private string $baseUrl;
    private HttpClient $http;

    public function __construct(
        private readonly string  $developerId,
        private readonly string  $userId,
        private readonly string  $userApiKey,
        private readonly ?string $locationId = null,
        private readonly bool    $sandbox = true,
        private readonly int     $timeout = 30,
    )
    {
        $this->baseUrl = $this->sandbox
            ? 'https://api.sandbox.fortis.tech'
            : 'https://api.fortis.tech';

        $builder = FortisAPIClientBuilder::init()
            ->environment($this->sandbox ? Environment::SANDBOX : Environment::PRODUCTION);

        /**
         * Reflective per-header credentials. Your builder exposes:
         *   - userIdCredentials(...)
         *   - userApiKeyCredentials(...)
         *   - developerIdCredentials(...)
         * with no credential builders shipped. We inspect the parameter type and pass either
         * the raw string or a best-effort object instance.
         */
        $applyCred = static function (object $b, string $method, string $value): object {
            if (!method_exists($b, $method)) {
                return $b;
            }
            try {
                $rm = new \ReflectionMethod($b, $method);
                $param = $rm->getParameters()[0] ?? null;
                if (!$param || !$param->hasType()) {
                    return $b->{$method}($value);
                }
                $type = $param->getType();
                $passString = static function () use ($b, $method, $value) {
                    return $b->{$method}($value);
                };
                if ($type instanceof \ReflectionUnionType) {
                    foreach ($type->getTypes() as $t) {
                        if ($t->isBuiltin() && $t->getName() === 'string') {
                            return $passString();
                        }
                        if (!$t->isBuiltin() && class_exists($t->getName())) {
                            $cls = $t->getName();
                            if (method_exists($cls, 'from')) return $b->{$method}($cls::from($value));
                            if (method_exists($cls, 'of')) return $b->{$method}($cls::of($value));
                            return $b->{$method}(new $cls($value));
                        }
                    }
                    return $passString();
                }
                if ($type->isBuiltin()) {
                    return $passString();
                }
                $cls = $type->getName();
                if (class_exists($cls)) {
                    if (method_exists($cls, 'from')) return $b->{$method}($cls::from($value));
                    if (method_exists($cls, 'of')) return $b->{$method}($cls::of($value));
                    return $b->{$method}(new $cls($value));
                }
                return $passString();
            } catch (\Throwable) {
                try {
                    return $b->{$method}($value);
                } catch (\Throwable) {
                    return $b;
                }
            }
        };

        $builder = $applyCred($builder, 'userIdCredentials', $this->userId);
        $builder = $applyCred($builder, 'userApiKeyCredentials', $this->userApiKey);
        $builder = $applyCred($builder, 'developerIdCredentials', $this->developerId);

        // Also attach headers as a harmless fallback (some SDKs read both)
        if (method_exists($builder, 'additionalHeaders')) {
            $builder = $builder->additionalHeaders([
                'developer-id' => $this->developerId,
                'user-id' => $this->userId,
                'user-api-key' => $this->userApiKey,
                'content-type' => 'application/json',
            ]);
        } elseif (method_exists($builder, 'globalHeaders')) {
            $builder = $builder->globalHeaders([
                'developer-id' => $this->developerId,
                'user-id' => $this->userId,
                'user-api-key' => $this->userApiKey,
                'content-type' => 'application/json',
            ]);
        }

        if (method_exists($builder, 'httpClientOptions')) {
            $builder = $builder->httpClientOptions(['timeout' => $this->timeout]);
        }

        $this->client = $builder->build();
        $this->http = new HttpClient([
            'base_uri' => $this->baseUrl . '/',
            'timeout' => $this->timeout,
        ]);
    }

    /** @param mixed $body */
    private function toArray(mixed $body): array
    {
        return json_decode(json_encode($body, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);
    }

    /** @return array{errors: array<string, array<int, string>>, code?: int} */
    private function errorArray(ApiException|\Throwable $e): array
    {
        $raw = ($e instanceof ApiException) && $e->isResponseAvailable() && $e->getHttpResponse() && method_exists($e->getHttpResponse(), 'getRawBody')
            ? $e->getHttpResponse()->getRawBody()
            : null;

        $decoded = is_string($raw) ? json_decode($raw, true) : null;

        return [
            'errors' => $decoded['errors'] ?? ['api' => [trim($e->getMessage())]],
            'code' => (int)$e->getCode(),
        ];
    }

    /** @return array<string,mixed> */
    public function saleTokenized(string $tokenId, int $amountMinor, ?string $orderNumber = null, ?string $locationId = null): array
    {
        try {
            try {
                $req = new V1TransactionsCcSaleTokenRequest($amountMinor, $tokenId);
            } catch (\Throwable) {
                $req = new V1TransactionsCcSaleTokenRequest($tokenId, $amountMinor);
            }
            if ($orderNumber !== null && method_exists($req, 'setOrderNumber')) {
                $req->setOrderNumber($orderNumber);
            }
            if (($locationId ?? $this->locationId) !== null && method_exists($req, 'setLocationId')) {
                $req->setLocationId($locationId ?? $this->locationId);
            }
            $res = $this->client->getTransactionsCreditCardController()->cCSaleTokenized($req);
            return $this->toArray($res);
        } catch (AuthValidationException $e) {
            return $this->fallbackPostJson(
                'v1/transactions/cc/sale/token',
                [
                    'token_id' => $tokenId,
                    'transaction_amount' => $amountMinor,
                    'order_number' => $orderNumber,
                    'location_id' => $locationId ?? $this->locationId,
                ]
            );
        } catch (ApiException $e) {
            return $this->errorArray($e);
        }
    }

    /** @return array<string,mixed> */
    public function saleKeyed(string $pan, string $expMMYY, ?string $cvv, int $amountMinor, ?string $orderNumber = null, ?string $locationId = null): array
    {
        try {
            try {
                $req = new V1TransactionsCcSaleKeyedRequest($amountMinor, $pan, $expMMYY);
            } catch (\Throwable) {
                $req = new V1TransactionsCcSaleKeyedRequest($pan, $expMMYY, $amountMinor);
            }
            if ($cvv !== null && method_exists($req, 'setCvv')) {
                $req->setCvv($cvv);
            }
            if ($orderNumber !== null && method_exists($req, 'setOrderNumber')) {
                $req->setOrderNumber($orderNumber);
            }
            if (($locationId ?? $this->locationId) !== null && method_exists($req, 'setLocationId')) {
                $req->setLocationId($locationId ?? $this->locationId);
            }
            $res = $this->client->getTransactionsCreditCardController()->cCSale($req);
            return $this->toArray($res);
        } catch (AuthValidationException $e) {
            return $this->fallbackPostJson(
                'v1/transactions/cc/sale/keyed',
                [
                    'account_number' => $pan,
                    'exp_date' => $expMMYY,
                    'cvv' => $cvv,
                    'transaction_amount' => $amountMinor,
                    'order_number' => $orderNumber,
                    'location_id' => $locationId ?? $this->locationId,
                ]
            );
        } catch (ApiException $e) {
            return $this->errorArray($e);
        }
    }

    /** @return array<string,mixed> */
    public function authTokenized(string $tokenId, int $amountMinor, ?string $orderNumber = null, ?string $locationId = null): array
    {
        try {
            try {
                $req = new V1TransactionsCcAuthOnlyTokenRequest($amountMinor, $tokenId);
            } catch (\Throwable) {
                $req = new V1TransactionsCcAuthOnlyTokenRequest($tokenId, $amountMinor);
            }
            if ($orderNumber !== null && method_exists($req, 'setOrderNumber')) {
                $req->setOrderNumber($orderNumber);
            }
            if (($locationId ?? $this->locationId) !== null && method_exists($req, 'setLocationId')) {
                $req->setLocationId($locationId ?? $this->locationId);
            }
            $res = $this->client->getTransactionsCreditCardController()->cCAuthOnlyTokenized($req);
            return $this->toArray($res);
        } catch (AuthValidationException $e) {
            return $this->fallbackPostJson(
                'v1/transactions/cc/auth-only/token',
                [
                    'token_id' => $tokenId,
                    'transaction_amount' => $amountMinor,
                    'order_number' => $orderNumber,
                    'location_id' => $locationId ?? $this->locationId,
                ]
            );
        } catch (ApiException $e) {
            return $this->errorArray($e);
        }
    }

    /** @return array<string,mixed> */
    public function authKeyed(string $pan, string $expMMYY, ?string $cvv, int $amountMinor, ?string $orderNumber = null, ?string $locationId = null): array
    {
        try {
            try {
                $req = new V1TransactionsCcAuthOnlyKeyedRequest($amountMinor, $pan, $expMMYY);
            } catch (\Throwable) {
                $req = new V1TransactionsCcAuthOnlyKeyedRequest($pan, $expMMYY, $amountMinor);
            }
            if ($cvv !== null && method_exists($req, 'setCvv')) {
                $req->setCvv($cvv);
            }
            if ($orderNumber !== null && method_exists($req, 'setOrderNumber')) {
                $req->setOrderNumber($orderNumber);
            }
            if (($locationId ?? $this->locationId) !== null && method_exists($req, 'setLocationId')) {
                $req->setLocationId($locationId ?? $this->locationId);
            }
            $res = $this->client->getTransactionsCreditCardController()->cCAuthOnly($req);
            return $this->toArray($res);
        } catch (AuthValidationException $e) {
            return $this->fallbackPostJson(
                'v1/transactions/cc/auth-only/keyed',
                [
                    'account_number' => $pan,
                    'exp_date' => $expMMYY,
                    'cvv' => $cvv,
                    'transaction_amount' => $amountMinor,
                    'order_number' => $orderNumber,
                    'location_id' => $locationId ?? $this->locationId,
                ]
            );
        } catch (ApiException $e) {
            return $this->errorArray($e);
        }
    }

    /** @return array<string,mixed> */
    public function capture(string $transactionId, int $amountMinor): array
    {
        try {
            try {
                $req = new V1TransactionsAuthCompleteRequest($amountMinor);
            } catch (\Throwable) {
                $req = new V1TransactionsAuthCompleteRequest();
                if (method_exists($req, 'setTransactionAmount')) {
                    $req->setTransactionAmount($amountMinor);
                }
            }
            $res = $this->client->getTransactionsUpdatesController()->authComplete($transactionId, $req);
            return $this->toArray($res);
        } catch (AuthValidationException $e) {
            return $this->fallbackPostJson(
                "v1/transactions/{$transactionId}/auth-complete",
                ['transaction_amount' => $amountMinor]
            );
        } catch (ApiException $e) {
            return $this->errorArray($e);
        }
    }

    /** @return array<string,mixed> */
    public function void(string $transactionId): array
    {
        try {
            $res = $this->client->getTransactionsUpdatesController()->void($transactionId);
            return $this->toArray($res);
        } catch (AuthValidationException $e) {
            return $this->fallbackPostJson("v1/transactions/{$transactionId}/void", []);
        } catch (ApiException $e) {
            return $this->errorArray($e);
        }
    }

    /** @return array<string,mixed> */
    public function refund(string $transactionId, int $amountMinor): array
    {
        try {
            // Try typed model if your SDK requires it
            if (class_exists('\\FortisAPILib\\Models\\V1TransactionsRefundRequest')) {
                $cls = '\\FortisAPILib\\Models\\V1TransactionsRefundRequest';
                try {
                    $req = new $cls($amountMinor);
                } catch (\Throwable) {
                    $req = new $cls();
                    if (method_exists($req, 'setTransactionAmount')) {
                        $req->setTransactionAmount($amountMinor);
                    }
                }
                $res = $this->client->getTransactionsUpdatesController()->refundTransaction($transactionId, $req);
            } else {
                // Some builds accept an array
                $res = $this->client->getTransactionsUpdatesController()->refundTransaction(
                    $transactionId,
                    ['transaction_amount' => $amountMinor]
                );
            }
            return $this->toArray($res);
        } catch (AuthValidationException $e) {
            return $this->fallbackPostJson(
                "v1/transactions/{$transactionId}/refund",
                ['transaction_amount' => $amountMinor]
            );
        } catch (ApiException $e) {
            return $this->errorArray($e);
        }
    }

    /** @return array<string,mixed> */
    public function get(string $transactionId): array
    {
        try {
            $res = $this->client->getTransactionsReadController()->getTransaction($transactionId);
            return $this->toArray($res);
        } catch (ApiException $e) {
            return $this->errorArray($e);
        }
    }

    /** @param array<string,mixed> $json @return array<string,mixed> */
    private function fallbackPostJson(string $path, array $json): array
    {
        // Remove nulls so we don’t send spurious fields
        $json = array_filter($json, static fn($v) => $v !== null);

        try {
            $resp = $this->http->request('POST', ltrim($path, '/'), [
                'headers' => [
                    'content-type' => 'application/json',
                    'accept' => 'application/json',
                    'developer-id' => $this->developerId,
                    'user-id' => $this->userId,
                    'user-api-key' => $this->userApiKey,
                ],
                'json' => $json,
            ]);
            $body = (string)$resp->getBody();
            /** @var array<string,mixed> $decoded */
            $decoded = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
            return $decoded;
        } catch (GuzzleException|\Throwable $e) {
            // Normalize fallback error to SDK-like shape
            return [
                'errors' => ['http' => [trim($e->getMessage())]],
                'code' => method_exists($e, 'getCode') ? (int)$e->getCode() : 0,
            ];
        }
    }
}
