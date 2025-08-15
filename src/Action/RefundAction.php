<?php
declare(strict_types=1);

namespace Vendor\FortisPlugin\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Refund;
use Vendor\FortisPlugin\Api\FortisApiInterface;

final class RefundAction implements ActionInterface, ApiAwareInterface
{
    use ApiAwareTrait;

    public function __construct()
    {
        $this->apiClass = \Vendor\FortisPlugin\Api\FortisApiInterface::class;
    }

    public function execute($request): void
    {
        /** @var Refund $request */
        RequestNotSupportedException::assertSupports($this, $request);

        // Use the model (array/ArrayObject), not a Sylius Payment object
        $model = (array)$request->getModel();

        $transactionId = $model['fortis_response']['data']['id']
            ?? $model['fortis_transaction_id']
            ?? null;

        // Prefer an explicit refund amount on the model; fall back to original amount if present
        $amountMinor = (int)($model['refund_amount_minor'] ?? $model['amount_minor'] ?? 0);

        /** @var FortisApiInterface $api */
        $api = $this->api;

        if (!$transactionId) {
            $model['fortis_refund_response'] = ['errors' => ['transaction' => ['Missing transaction id']]];
            $request->setModel($model);
            return;
        }

        if ($amountMinor <= 0) {
            $model['fortis_refund_response'] = ['errors' => ['amount' => ['Refund amount is required']]];
            $request->setModel($model);
            return;
        }

        $resp = $api->refund((string)$transactionId, $amountMinor);
        $model['fortis_refund_response'] = $resp;
        $request->setModel($model);
    }

    public function supports($request): bool
    {
        return $request instanceof Refund;
    }
}
