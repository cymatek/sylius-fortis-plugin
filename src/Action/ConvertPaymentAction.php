<?php

declare(strict_types=1);

namespace Vendor\FortisPlugin\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Convert;
use Sylius\Component\Core\Model\PaymentInterface as SyliusPayment;

final class ConvertPaymentAction implements ActionInterface
{
    public function execute($request): void
    {
        /** @var Convert $request */
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var SyliusPayment $payment */
        $payment = $request->getSource();
        $details = $payment->getDetails();

        $amountMinor = $payment->getAmount(); // cents, Sylius minor unit
        $orderNumber = (string)($details['order_number'] ?? $payment->getOrder()?->getNumber() ?? $payment->getId());
        $locationId = $details['location_id'] ?? null;

        $result = [
            'amount_minor' => $amountMinor,
            'order_number' => $orderNumber,
            'location_id' => $locationId,
        ];

        // Prefer token
        if (!empty($details['payment_token'])) {
            $result['payment_method'] = 'token';
            $result['token_id'] = $details['payment_token'];
        } elseif (!empty($details['cc_number']) && !empty($details['exp_date'])) {
            $result['payment_method'] = 'keyed';
            $result['account_number'] = $details['cc_number'];
            $result['exp_date'] = $details['exp_date']; // MMYY
            if (!empty($details['cvv'])) {
                $result['cvv'] = $details['cvv'];
            }
        }

        $request->setResult($result);
    }

    public function supports($request): bool
    {
        return $request instanceof Convert && $request->getSource() instanceof SyliusPayment && 'array' === $request->getTo();
    }
}
