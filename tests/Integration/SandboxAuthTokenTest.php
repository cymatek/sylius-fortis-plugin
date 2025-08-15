<?php
declare(strict_types=1);

namespace Vendor\FortisPlugin\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Vendor\FortisPlugin\Api\FortisSdkAdapter;

final class SandboxAuthTokenTest extends TestCase
{
    public function test_sale_tokenized_succeeds_or_returns_valid_errors(): void
    {
        if ((string)getenv('FORTIS_RUN_LIVE') !== '1') {
            $this->markTestSkipped('FORTIS_RUN_LIVE not set');
        }

        $token = getenv('FORTIS_TEST_TOKEN') ?: '';
        if ($token === '') {
            $script = \dirname(__DIR__, 2) . '/scripts/mint-fortis-token.php';
            if (is_file($script)) {
                $out = shell_exec('php ' . escapeshellarg($script));
                $token = $out ? trim($out) : '';
                if ($token !== '') {
                    putenv('FORTIS_TEST_TOKEN=' . $token);
                }
            }
        }
        if ($token === '') {
            $this->markTestSkipped('FORTIS_TEST_TOKEN not set and could not mint one');
        }

        $api = new FortisSdkAdapter(
            developerId: getenv('FORTIS_DEVELOPER_ID') ?: '',
            userId: getenv('FORTIS_USER_ID') ?: '',
            userApiKey: getenv('FORTIS_USER_API_KEY') ?: '',
            locationId: getenv('FORTIS_LOCATION_ID') ?: '',
            sandbox: (bool)((int)(getenv('FORTIS_SANDBOX') ?: '1')),
        );

        $res = $api->saleTokenized($token, 123, 'tok-test-' . time());
        $this->assertIsArray($res);
        $this->assertTrue(
            isset($res['data']['id']) || isset($res['transaction']['id']) || isset($res['errors']),
            'Expected success or errors array, got: ' . json_encode($res)
        );
    }
}
