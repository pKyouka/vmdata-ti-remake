<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Http\Kernel;

$kernel = new Kernel(app(), app('router'));

$ref = new ReflectionClass($kernel);

$props = ['routeMiddleware', 'middlewareAliases'];

foreach ($props as $prop) {
    if ($ref->hasProperty($prop)) {
        $p = $ref->getProperty($prop);
        $p->setAccessible(true);
        $val = $p->getValue($kernel);
        echo $prop . " => ";
        if (is_array($val)) {
            echo array_key_exists('role', $val) ? 'role mapped' : 'role missing';
        } else {
            echo gettype($val);
        }
        echo PHP_EOL;
    } else {
        echo "$prop => (not present)\n";
    }
}
