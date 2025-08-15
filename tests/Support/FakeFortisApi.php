<?php
declare(strict_types=1);

namespace Vendor\FortisPlugin\Tests\Support;

use Vendor\FortisPlugin\Api\FortisApiInterface;

final class FakeFortisApi implements FortisApiInterface
{
    public function saleTokenized(string $tokenId, int $amountMinor, ?string $orderNumber = null, ?string $locationId = null): array
    {
        return ['data' => ['id' => 'tx_sale', 'type' => 'sale', 'status_code' => 1000]];
    }

    public function saleKeyed(string $pan, string $expMMYY, ?string $cvv, int $amountMinor, ?string $orderNumber = null, ?string $locationId = null): array
    {
        return ['errors' => ['account_number' => ['blocked in unit tests']]];
    }

    public function authTokenized(string $tokenId, int $amountMinor, ?string $orderNumber = null, ?string $locationId = null): array
    {
        return ['data' => ['id' => 'tx_auth', 'type' => 'auth', 'status_code' => 1000]];
    }

    public function authKeyed(string $pan, string $expMMYY, ?string $cvv, int $amountMinor, ?string $orderNumber = null, ?string $locationId = null): array
    {
        return ['errors' => ['account_number' => ['blocked in unit tests']]];
    }

    public function capture(string $transactionId, int $amountMinor): array
    {
        return ['data' => ['id' => 'tx_cap', 'type' => 'capture', 'status_code' => 1000]];
    }

    public function void(string $transactionId): array
    {
        return ['data' => ['id' => $transactionId, 'type' => 'void', 'status_code' => 1000]];
    }

    public function refund(string $transactionId, int $amountMinor): array
    {
        return ['data' => ['id' => 'rf_1', 'type' => 'refund', 'status_code' => 1000]];
    }

    public function get(string $transactionId): array
    {
        return ['data' => ['id' => $transactionId, 'type' => 'sale', 'status_code' => 1000]];
    }
}
