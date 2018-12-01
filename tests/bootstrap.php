<?php

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Debug\Debug;
use Symfony\Component\Filesystem\Filesystem;

$time = microtime(true);

echo "\nBootstraping test suite...";

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

define('NO_RECREATE_DB', '1' === $getenv('NO_RECREATE_DB', false));
define('CLEAR_CACHE', '1' === $getenv('CLEAR_CACHE', true));
define('RECREATE_DB', '1' === $getenv('RECREATE_DB', false));

gc_disable();
ini_set('memory_limit', -1);
error_reporting(E_ALL);

$rootDir = __DIR__.'/..';

define('BUILD_DIR', $rootDir.'/build');

define('DATABASE_TEST_FILE', $rootDir.'/build/database_test.db');
define('DATABASE_REFERENCE_FILE', $rootDir.'/build/database_reference.db');

$file = $rootDir.'/vendor/autoload.php';
if (!file_exists($file)) {
    throw new RuntimeException('Install dependencies to run test suite.');
}

require $file;

if ($debug = ('1' === $getenv('APP_DEBUG', true))) {
    echo "\nEnabling debug";
    Debug::enable();
}

$application = null;

$runCommand = function (string $cmd) use (&$application, $getenv, $debug): void {
    if (!$application) {
        echo "\nCreating kernel & console application for bootstraping commands";
        $kernel = new Kernel($getenv('APP_ENV') ?: 'test', $debug);
        $application = new Application($kernel);
        $application->setAutoExit(false);
    }

    $code = $application->run(new StringInput($cmd));
    if ($code) {
        throw new \RuntimeException(sprintf('Command %s failed with code "%d".', $cmd, $code));
    }
};

if (CLEAR_CACHE) {
    echo "\nClearing cache";
    $runCommand('cache:clear --no-warmup');
    $runCommand('cache:warmup');
}

$fs = new Filesystem();

if (RECREATE_DB) {
    echo "\nRemoving existing database files as explicitly set by config";
    $fs->remove(DATABASE_REFERENCE_FILE);
    $fs->remove(DATABASE_TEST_FILE);
}

if ($fs->exists(DATABASE_REFERENCE_FILE)) {
    echo "\nCopying reference file to test file for first execution";
    // Reset database everytime
    $fs->copy(DATABASE_REFERENCE_FILE, DATABASE_TEST_FILE, true);
    goto end;
}

if (NO_RECREATE_DB && file_exists($rootDir.'/build/database_reference.db')) {
    echo "\nDatabase exists";
    goto end;
}

if (!is_dir($rootDir.'/build')) {
    $fs->mkdir($rootDir.'/build');
}

if (is_dir($rootDir.'/build/screenshots/')) {
    $fs->remove($rootDir.'/build/screenshots/');
}
$fs->mkdir($rootDir.'/build/screenshots/');

if ($fs->exists(DATABASE_TEST_FILE)) {
    echo "\nRemoving test database";
    $fs->remove(DATABASE_TEST_FILE);
}

echo "\nCreating test database";

$runCommand('doctrine:database:create');
$runCommand('doctrine:schema:create --no-interaction');
$runCommand('doctrine:fixtures:load --append');

echo "\nCopying test database to reference file";
$fs->copy(DATABASE_TEST_FILE, DATABASE_REFERENCE_FILE);

$kernel->shutdown();

end:

// Unset everything so PHPUnit can't dump these globals.
unset($rootDir, $file, $autoload, $kernel, $application, $runCommand, $fs);

$seconds = number_format(microtime(true) - $time, 2);

echo "\nBootstraped test suite in $seconds seconds.\n";
