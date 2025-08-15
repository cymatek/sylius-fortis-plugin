<?php
declare(strict_types=1);

namespace Vendor\FortisPlugin\Tests\Support;

use Payum\Core\GatewayInterface;
use Payum\Core\Request\Convert;

final class FakeGateway implements GatewayInterface
{
    public function execute($request, $catchReply = false)
    {
        if ($request instanceof Convert) {
            // Provide the converted details the actions expect
            $request->setResult([
                'payment_method' => 'token',
                'token_id' => 'tok_test_123',
                'amount_minor' => 1234,
                'order_number' => 'ORDER-1001',
                'location_id' => 'loc_abc',
            ]);
        }
    }

    public function addAction($action, $forcePrepend = false): void
    {
    }

    public function addExtension($extension, $forcePrepend = false): void
    {
    }

    public function addApi($api, $forcePrepend = false): void
    {
    }
}
