<?php

declare(strict_types=1);

namespace Vendor\FortisPlugin\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Authorize;
use Payum\Core\Request\Convert;
use Vendor\FortisPlugin\Api\FortisSdkAdapter;
use Vendor\FortisPlugin\Api\FortisApiInterface;

final class AuthorizeAction implements ActionInterface, ApiAwareInterface, GatewayAwareInterface
{
    use ApiAwareTrait, GatewayAwareTrait;

    public function __construct()
    {
        $this->apiClass = FortisApiInterface::class;
    }

    public function execute($request): void
    {
        /** @var Authorize $request */
        RequestNotSupportedException::assertSupports($this, $request);

        $model = (array)$request->getModel();
        $convert = new Convert($request->getFirstModel(), 'array');
        $this->gateway->execute($convert);
        /** @var array $d */
        $d = $convert->getResult();

        /** @var FortisSdkAdapter $api */
        $api = $this->api;

        $resp = match ($d['payment_method'] ?? 'token') {
            'token' => $api->authTokenized($d['token_id'], $d['amount_minor'], $d['order_number'], $d['location_id'] ?? null),
            'keyed' => $api->authKeyed($d['account_number'], $d['exp_date'], $d['cvv'] ?? null, $d['amount_minor'], $d['order_number'], $d['location_id'] ?? null),
            default => ['errors' => ['payment_method' => ['Unsupported method']]],
        };

        $model['fortis_response'] = $resp;
        $request->setModel($model);
    }

    public function supports($request): bool
    {
        return $request instanceof Authorize;
    }
}
