<?php
declare(strict_types=1);

namespace Vendor\FortisPlugin\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class VendorFortisPluginExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
        if (is_file(__DIR__ . '/../Resources/config/routing.yaml')) {
            // nothing to load; routes are imported by the host app
        }
    }
}
