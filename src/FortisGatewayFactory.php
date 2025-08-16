<?php
declare(strict_types=1);

namespace Vendor\FortisPlugin;

use Payum\Core\GatewayFactory;
use Payum\Core\Bridge\Spl\ArrayObject as PayumArrayObject;

final class FortisGatewayFactory extends GatewayFactory
{
    /**
     * Payum passes a PayumArrayObject here; use its helpers instead of array_replace().
     *
     * @param array|\ArrayObject $config
     */
    protected function populateConfig(array &$config): void
    {
        // Normalize to PayumArrayObject
        /** @var PayumArrayObject $config */
        $config = PayumArrayObject::ensureArrayObject($config);

        // Basic factory metadata
        $config->defaults([
            'payum.factory_name'  => 'fortis',
            'payum.factory_title' => 'Fortis',
        ]);

        // Default gateway options (what Admin form/edit persists)
        $config->offsetSet('payum.default_options', [
            'developer_id' => null,
            'user_id'      => null,
            'user_api_key' => null,
            'location_id'  => null, // optional default; can be overridden per payment details
            'sandbox'      => true,
            'timeout'      => 30,
        ]);
        $config->defaults($config['payum.default_options']);

        // API factory (adjust class if you’re using FortisSdkAdapter instead of FortisApi)
        if (false == $config->offsetExists('payum.api')) {
            $config['payum.api'] = static function (PayumArrayObject $config) {
                // If you’re using the SDK adapter, swap to: return new Api\FortisSdkAdapter(...)
                return new Api\FortisApi(
                    developerId: (string) $config['developer_id'],
                    userId:      (string) $config['user_id'],
                    userApiKey:  (string) $config['user_api_key'],
                    sandbox:     (bool)   $config['sandbox'],
                    timeout:     (int)    $config['timeout'],
                );
            };
        }

        // Templating paths (safe cast for the existing value)
        $config['payum.paths'] = array_replace([
            'VendorFortisPlugin' => __DIR__ . '/Resources/views',
        ], (array) ($config['payum.paths'] ?? []));
    }
}
