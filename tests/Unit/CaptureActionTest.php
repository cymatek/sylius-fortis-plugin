<?php
declare(strict_types=1);

namespace Vendor\FortisPlugin\Tests\Unit;

use Payum\Core\Request\Capture;
use Payum\Core\Request\GetHumanStatus;
use PHPUnit\Framework\TestCase;
use Vendor\FortisPlugin\Action\CaptureAction;
use Vendor\FortisPlugin\Action\StatusAction;
use Vendor\FortisPlugin\Tests\Support\FakeFortisApi;
use Vendor\FortisPlugin\Tests\Support\FakeGateway;

final class CaptureActionTest extends TestCase
{
    public function test_capture_marks_captured(): void
    {
        $api = new FakeFortisApi();
        $gateway = new FakeGateway();

        $action = new CaptureAction();
        $action->setApi($api);
        $action->setGateway($gateway);

        $req = new Capture([]);
        $action->execute($req);

        $model = $req->getModel();
        $this->assertSame('tx_sale', $model['fortis_response']['data']['id']);

        $status = new GetHumanStatus($req);
        $status->setModel(['fortis_response' => $model['fortis_response']]);
        (new StatusAction())->execute($status);
        $this->assertTrue($status->isCaptured());
    }
}
