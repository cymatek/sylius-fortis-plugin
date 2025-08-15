<?php
declare(strict_types=1);

namespace Vendor\FortisPlugin\Tests\Unit;

use Payum\Core\Request\Cancel;
use Payum\Core\Request\Refund;
use PHPUnit\Framework\TestCase;
use Vendor\FortisPlugin\Action\CancelAction;
use Vendor\FortisPlugin\Action\RefundAction;
use Vendor\FortisPlugin\Tests\Support\FakeFortisApi;

final class RefundAndCancelActionTest extends TestCase
{
    public function test_refund_and_void(): void
    {
        $api = new FakeFortisApi();

        $refund = new RefundAction();
        $refund->setApi($api);

        $cancel = new CancelAction();
        $cancel->setApi($api);

        // Seed the model to simulate a prior charge and provide a refund amount
        $model = [
            'fortis_response' => ['data' => ['id' => 'tx_1']],
            'refund_amount_minor' => 1234, // $12.34
        ];

        $refundReq = new Refund($model);
        $refund->execute($refundReq);
        $this->assertSame('rf_1', $refundReq->getModel()['fortis_refund_response']['data']['id']);

        $cancelReq = new Cancel($model);
        $cancel->execute($cancelReq);
        $this->assertSame('tx_1', $cancelReq->getModel()['fortis_void_response']['data']['id']);
    }
}
