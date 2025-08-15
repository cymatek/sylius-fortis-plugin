<?php
declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

$builder = \FortisAPILib\FortisAPIClientBuilder::init();

echo "Builder class: ", get_class($builder), PHP_EOL, PHP_EOL;

$methods = array_filter(get_class_methods($builder), function ($m) {
    $m = strtolower($m);
    return str_contains($m, 'auth') || str_contains($m, 'cred') || str_contains($m, 'header');
});
echo "Builder methods (auth-ish):", PHP_EOL;
foreach ($methods as $m) echo " - $m", PHP_EOL;

$namespaces = ['\\FortisAPILib\\Authentication', '\\FortisAPILib\\Auth'];
echo PHP_EOL, "Credential builders found:", PHP_EOL;
foreach ($namespaces as $ns) {
    $dir = __DIR__ . '/../vendor/apimatic-sdks/fortisapi/src' . str_replace('\\', '/', $ns) . '/';
    if (!is_dir($dir)) continue;
    foreach (glob($dir.'*.php') as $file) {
        $class = $ns . '\\' . pathinfo($file, PATHINFO_FILENAME);
        if (class_exists($class)) {
            if (str_contains(strtolower($class), 'credential') && method_exists($class, 'init')) {
                echo " - $class", PHP_EOL;
            }
        }
    }
}
