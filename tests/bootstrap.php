<?php

echo "\n[Test bootstrap] Bootstraping test suite...";

require __DIR__.'/../config/bootstrap.php';

if (\function_exists('xdebug_set_filter')) {
    echo "\n[Test bootstrap] Xdebug enabled, activate coverage whitelist filter...";
    \xdebug_set_filter(
        \constant('XDEBUG_FILTER_CODE_COVERAGE'),
        \constant('XDEBUG_PATH_WHITELIST'),
        [
            \dirname(__DIR__).'/src/',
        ]
    );
}

echo "\n[Test bootstrap] Done!";
