<?php

use Phalcon\Loader;

$loader = new Loader();

/**
 * Register Namespaces
 */
$loader->registerNamespaces([
    'PhalconTryout\Models' => APP_PATH . '/common/models/',
    'PhalconTryout'        => APP_PATH . '/common/library/',
]);

/**
 * Register module classes
 */
$loader->registerClasses([
    PhalconTryout\Modules\Frontend\Module::class => APP_PATH . '/modules/frontend/Module.php',
    PhalconTryout\Modules\Cli\Module::class => APP_PATH . '/modules/cli/Module.php'
]);

$loader->register();
