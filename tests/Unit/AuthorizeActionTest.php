<?php
declare(strict_types=1);

namespace Vendor\FortisPlugin\Tests\Unit;

use Payum\Core\Request\Authorize;
use Payum\Core\Request\GetHumanStatus;
use PHPUnit\Framework\TestCase;
use Vendor\FortisPlugin\Action\AuthorizeAction;
use Vendor\FortisPlugin\Action\StatusAction;
use Vendor\FortisPlugin\Tests\Support\FakeFortisApi;
use Vendor\FortisPlugin\Tests\Support\FakeGateway;

final class AuthorizeActionTest extends TestCase
{
    public function test_authorize_marks_authorized(): void
    {
        $api = new FakeFortisApi();
        $gateway = new FakeGateway();

        $action = new AuthorizeAction();
        $action->setApi($api);
        $action->setGateway($gateway);

        $req = new Authorize([]);
        $action->execute($req);

        $model = $req->getModel();
        $this->assertSame('tx_auth', $model['fortis_response']['data']['id']);

        $status = new GetHumanStatus($req);
        $status->setModel(['fortis_response' => $model['fortis_response']]);
        (new StatusAction())->execute($status);
        $this->assertTrue($status->isAuthorized());
    }
}
