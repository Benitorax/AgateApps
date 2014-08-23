<?php

if (in_array(@$_SERVER['REMOTE_ADDR'], array(
        '127.0.0.1',
        '127.0.0.1:8888',
        '127.0.0.1:8080',
)) || preg_match('~\.esteren\.dev(:[0-9]+)?$~', $_SERVER['REMOTE_ADDR'])) {
    // Permet en local d'utiliser app_dev_fast par défaut
    include 'app_dev_fast.php';
}

use Symfony\Component\ClassLoader\ApcClassLoader;
use Symfony\Component\HttpFoundation\Request;

$loader = require_once __DIR__.'/../var/bootstrap.php.cache';

// Use APC for autoloading to improve performance.
// Change 'sf2' to a unique prefix in order to prevent cache key conflicts
// with other applications also using APC.
/*
$apcLoader = new ApcClassLoader('sf2_corahnrin', $loader);
$loader->unregister();
$apcLoader->register(true);
//*/

require_once __DIR__.'/../app/AppKernel.php';
require_once __DIR__.'/../app/AppCache.php';

$kernel = new AppKernel('prod', false);
$kernel->loadClassCache();
$kernel = new AppCache($kernel);

// When using the HttpCache, you need to call the method in your front controller instead of relying on the configuration parameter
//Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
