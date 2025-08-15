<?php

declare(strict_types=1);

namespace Vendor\FortisPlugin\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Cancel;
use Vendor\FortisPlugin\Api\FortisSdkAdapter;
use Vendor\FortisPlugin\Api\FortisApiInterface;

final class CancelAction implements ActionInterface, ApiAwareInterface
{
    use ApiAwareTrait;

    public function __construct()
    {
        $this->apiClass = FortisApiInterface::class;
    }

    public function execute($request): void
    {
        /** @var Cancel $request */
        RequestNotSupportedException::assertSupports($this, $request);
        $model = (array)$request->getModel();
        $transactionId = $model['fortis_response']['data']['id'] ?? $model['fortis_transaction_id'] ?? null;

        /** @var FortisSdkAdapter $api */
        $api = $this->api;
        $resp = $api->void((string)$transactionId);
        $model['fortis_void_response'] = $resp;
        $request->setModel($model);
    }

    public function supports($request): bool
    {
        return $request instanceof Cancel;
    }
}
