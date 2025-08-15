<?php
declare(strict_types=1);

namespace Vendor\FortisPlugin;

use Payum\Core\GatewayFactory;
use Vendor\FortisPlugin\Api\FortisApiInterface;
use Vendor\FortisPlugin\Api\FortisSdkAdapter;

final class FortisGatewayFactory extends GatewayFactory
{
    protected function populateConfig(\ArrayObject $config): void
    {
        $config['payum.factory_name'] = 'fortis';
        $config['payum.factory_title'] = 'Fortis';

        $config['payum.default_options'] = [
            'developer_id' => null,
            'user_id' => null,
            'user_api_key' => null,
            'location_id' => null,
            'sandbox' => true,
            'timeout' => 30,
        ];
        $config = array_replace($config, $config['payum.default_options']);

        if (!isset($config['payum.api'])) {
            $config['payum.api'] = static function (array $config): FortisApiInterface {
                return new FortisSdkAdapter(
                    developerId: (string)$config['developer_id'],
                    userId: (string)$config['user_id'],
                    userApiKey: (string)$config['user_api_key'],
                    locationId: $config['location_id'] ? (string)$config['location_id'] : null,
                    sandbox: (bool)$config['sandbox'],
                    timeout: (int)$config['timeout']
                );
            };
        }
    }
}
