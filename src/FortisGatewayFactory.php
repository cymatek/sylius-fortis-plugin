<?php
declare(strict_types=1);

namespace Vendor\FortisPlugin;

use Payum\Core\Bridge\Spl\ArrayObject as PayumArrayObject;
use Payum\Core\GatewayFactory;
use Vendor\FortisPlugin\Api\FortisSdkAdapter; // or FortisApi if that's what you use

final class FortisGatewayFactory extends GatewayFactory
{
    protected function populateConfig(PayumArrayObject $config): void
    {
        // Factory metadata
        $config->defaults([
            'payum.factory_name'  => 'fortis',
            'payum.factory_title' => 'Fortis',
        ]);

        // Default gateway options
        $config->defaults([
            'payum.default_options' => [
                'developer_id' => null,
                'user_id'      => null,
                'user_api_key' => null,
                'location_id'  => null, // optional
                'sandbox'      => true,
                'timeout'      => 30,
            ],
        ]);

        /** @var array<string,mixed> $defaults */
        $defaults = $config['payum.default_options'];
        $config->defaults($defaults);

        // Build API lazily
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

        // Register Twig paths ONLY if the folder exists
        $paths = $config['payum.paths'] ?? [];
        $paths = is_array($paths) ? $paths : (array) $paths;

        $pluginViews = __DIR__ . '/Resources/views';
        if (is_dir($pluginViews)) {
            $paths = array_replace([
                'VendorFortisPlugin' => $pluginViews,
            ], $paths);
        }

        $config['payum.paths'] = $paths;
    }
}
