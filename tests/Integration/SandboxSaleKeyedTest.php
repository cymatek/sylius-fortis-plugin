<?php
declare(strict_types=1);

namespace Vendor\FortisPlugin\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Vendor\FortisPlugin\Api\FortisSdkAdapter;

final class SandboxSaleKeyedTest extends TestCase
{
    public function test_sale_keyed_succeeds_or_returns_valid_errors(): void
    {
        if ((string) getenv('FORTIS_RUN_LIVE') !== '1') {
            $this->markTestSkipped('FORTIS_RUN_LIVE not set (set FORTIS_RUN_LIVE=1 to hit sandbox).');
        }

        $cc  = getenv('FORTIS_TEST_CC') ?: '';
        $exp = getenv('FORTIS_TEST_EXP') ?: '';
        $cvv = getenv('FORTIS_TEST_CVV') ?: '';

        if ($cc === '' || $exp === '' || $cvv === '') {
            $this->markTestSkipped(
                'Keyed test requires FORTIS_TEST_CC, FORTIS_TEST_EXP (MMYY), FORTIS_TEST_CVV. '
                .'Add them to .env.local to run this test.'
            );
        }

        $api = new FortisSdkAdapter(
            developerId: getenv('FORTIS_DEVELOPER_ID') ?: '',
            userId:      getenv('FORTIS_USER_ID') ?: '',
            userApiKey:  getenv('FORTIS_USER_API_KEY') ?: '',
            locationId:  getenv('FORTIS_LOCATION_ID') ?: '',
            sandbox:     (bool) ((int) (getenv('FORTIS_SANDBOX') ?: '1')),
        );

        $res = $api->saleKeyed($cc, $exp, $cvv, 123, 'keyed-test-'.time());
        $this->assertIsArray($res);
        $this->assertTrue(
            isset($res['data']['id']) || isset($res['transaction']['id']) || isset($res['errors']),
            'Expected success or errors array, got: '.json_encode($res)
        );
    }
}
