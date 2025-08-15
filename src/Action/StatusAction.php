<?php
declare(strict_types=1);

namespace Vendor\FortisPlugin\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\GetHumanStatus;

final class StatusAction implements ActionInterface
{
    public function execute($request): void
    {
        /** @var GetHumanStatus $request */
        RequestNotSupportedException::assertSupports($this, $request);

        // read the array model directly
        $model = (array)$request->getModel();
        $resp = $model['fortis_response'] ?? null;

        if (!$resp) {
            $request->markNew();
            return;
        }
        if (isset($resp['errors']) && !empty($resp['errors'])) {
            $request->markFailed();
            return;
        }

        $data = $resp['data'] ?? [];
        $type = $data['type'] ?? null;                 // 'sale' | 'auth' | ...
        $statusCode = (int)($data['status_code'] ?? 0);     // Fortis success >= 1000 (adjust if needed)

        if ($statusCode >= 1000) {
            if ($type === 'auth' || $type === 'authonly') {
                $request->markAuthorized();
                return;
            }
            $request->markCaptured();
            return;
        }

        $request->markUnknown();
    }

    public function supports($request): bool
    {
        return $request instanceof GetHumanStatus;
    }
}
