<?php
declare(strict_types=1);

namespace Vendor\FortisPlugin;

use Payum\Core\Bridge\Spl\ArrayObject as PayumArrayObject;
use Payum\Core\GatewayFactory;
use Vendor\FortisPlugin\Api\FortisSdkAdapter;

final class FortisGatewayFactory extends GatewayFactory
{
    protected function populateConfig(PayumArrayObject $config): void
    {
        // Factory metadata
        $config->defaults([
            'payum.factory_name'  => 'fortis',
            'payum.factory_title' => 'Fortis',
        ]);

        // Default gateway options (persisted on GatewayConfig)
        $config->defaults([
            'payum.default_options' => [
                'developer_id' => null,
                'user_id'      => null,
                'user_api_key' => null,
                'location_id'  => null, // optional; you can override per-payment in details
                'sandbox'      => true,
                'timeout'      => 30,
            ],
        ]);

        // Apply defaults into the root config
        /** @var array<string,mixed> $defaults */
        $defaults = $config['payum.default_options'];
        $config->defaults($defaults);

        // Build the API if not already provided
        if (false === $config->offsetExists('payum.api')) {
            $config['payum.api'] = static function (PayumArrayObject $config) {
                return new FortisSdkAdapter(
                    developerId: (string) $config['developer_id'],
                    userId:      (string) $config['user_id'],
                    userApiKey:  (string) $config['user_api_key'],
                    locationId:  isset($config['location_id']) && $config['location_id'] !== '' ? (string) $config['location_id'] : null,
                    sandbox:     (bool)   $config['sandbox'],
                    timeout:     (int)    $config['timeout'],
                );
            };
        }

        // Template paths (optional)
        $paths = $config['payum.paths'] ?? [];
        $paths = is_array($paths) ? $paths : (array) $paths;
        $paths = array_replace([
            'VendorFortisPlugin' => __DIR__ . '/Resources/views',
        ], $paths);
        $config['payum.paths'] = $paths;
    }
}
