<?php

declare(strict_types=1);

namespace Vendor\FortisPlugin\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class FortisWebhookController
{
    #[Route('/payment/fortis/notify', name: 'vendor_fortis_notify', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(), true);
        // TODO: locate payment by transaction id; update details and/or trigger status sync
        return new JsonResponse(['ok' => true]);
    }
}
