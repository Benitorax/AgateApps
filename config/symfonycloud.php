<?php

$getenv = function (string $name, $default = null) {
    if (isset($_ENV[$name])) {
        return $_ENV[$name];
    }

    if (isset($_SERVER[$name]) && 0 !== strpos($name, 'HTTP_')) {
        return $_SERVER[$name];
    }

    if (false === ($env = getenv($name)) || null === $env) {
        return $default;
    }

    return $env;
};

$setEnv = function(string $name, string $value) use ($container): void {
    $container->setParameter("env($name)", $_ENV[$name] = $_SERVER[$name] = $value);
};

$relationships = $getenv('SYMFONY_RELATIONSHIPS');
if (!$relationships) {
    return;
}
$relationships = json_decode(base64_decode($relationships), true);

if (!isset($relationships['database'])) {
    throw new \RuntimeException('SYMFONY_RELATIONSHIPS env variable does not contains database.');
}
$db = $relationships['database'][0];
$dbLegacy = $relationships['database_legacy'][0];

$setEnv('DATABASE_URL', sprintf(
    '%s://%s:%s@%s:%s/%s',
    $db['scheme'],
    $db['username'],
    $db['password'],
    $db['host'],
    $db['port'],
    $db['path']
));

$setEnv('DATABASE_URL_LEGACY', sprintf(
    '%s://%s:%s@%s:%s/%s',
    $dbLegacy['scheme'],
    $dbLegacy['username'],
    $dbLegacy['password'],
    $dbLegacy['host'],
    $dbLegacy['port'],
    $dbLegacy['path']
));

$container->setParameter('env(RELEASE_VERSION)', $getenv('SYMFONY_TREE_ID'));
$container->setParameter('env(RELEASE_DATE)', $getenv('SYMFONY_TREE_ID'));
