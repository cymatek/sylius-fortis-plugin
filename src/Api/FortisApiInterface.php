<?php
declare(strict_types=1);

namespace Vendor\FortisPlugin\Api;

interface FortisApiInterface
{
    /** @return array<string,mixed> */
    public function saleTokenized(string $tokenId, int $amountMinor, ?string $orderNumber = null, ?string $locationId = null): array;

    /** @return array<string,mixed> */
    public function saleKeyed(string $pan, string $expMMYY, ?string $cvv, int $amountMinor, ?string $orderNumber = null, ?string $locationId = null): array;

    /** @return array<string,mixed> */
    public function authTokenized(string $tokenId, int $amountMinor, ?string $orderNumber = null, ?string $locationId = null): array;

    /** @return array<string,mixed> */
    public function authKeyed(string $pan, string $expMMYY, ?string $cvv, int $amountMinor, ?string $orderNumber = null, ?string $locationId = null): array;

    /** @return array<string,mixed> */
    public function capture(string $transactionId, int $amountMinor): array;

    /** @return array<string,mixed> */
    public function void(string $transactionId): array;

    /** @return array<string,mixed> */
    public function refund(string $transactionId, int $amountMinor): array;

    /** @return array<string,mixed> */
    public function get(string $transactionId): array;
}
