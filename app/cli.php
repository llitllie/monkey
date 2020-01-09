<?php

use Phalcon\Di\FactoryDefault\Cli as CliDI;
use Phalcon\Cli\Console as ConsoleApp;
use Phalcon\Loader;

error_reporting(E_ALL);
defined('BASE_PATH') || define('BASE_PATH', dirname(__DIR__));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

// Using the CLI factory default services container
$di = new CliDI();

/**
 * Register the autoloader and tell it to register the tasks directory
 */
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . '/tasks',
    ]
);

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerNamespaces(
    [
        'Peg\Models'      => APP_PATH .'/models',
        'Sim'             => APP_PATH .'/library//Sim',
        'Peg\Jobs'        => APP_PATH . '/jobs',
        'Peg\Components'  => APP_PATH . '/components'
    ]
);

$loader->register();

// Load the configuration file (if any)
$configFile = __DIR__ . '/config/config.php';

if (is_readable($configFile)) {
    $config = include $configFile;
    
    $di->set('config', $config);
}

/**
 * Read services
 */
include APP_PATH . '/config/services.php';

/**
 * error handle
 */
include APP_PATH . '/config/error.php';


// Create a console application
$console = new ConsoleApp();

$console->setDI($di);

/**
 * Process the console arguments
 */
$arguments = [];

foreach ($argv as $k => $arg) {
    if ($k === 1) {
        $arguments['task'] = $arg;
    } elseif ($k === 2) {
        $arguments['action'] = $arg;
    } elseif ($k >= 3) {
        $arguments['params'][] = $arg;
    }
}

$di->setShared("console", $console);

try {
    $master = file_exists(APP_PATH.'/config/master.conf');
    if($master){
        // Handle incoming arguments
        $console->handle($arguments);
    }
} catch (\Phalcon\Exception $e) {
    $message = $e->getCode() . ' : ' . $e->getMessage() . ', '. $e->getFile() . ' : line ' . $e->getLine();
    $di->getShared('logger')->critical($message);
    fwrite(STDERR, $message. PHP_EOL);
    exit(1);
} catch (\Throwable $throwable) {
    $message = $throwable->getCode() . ' : ' . $throwable->getMessage() . ', '. $throwable->getFile() . ' : line ' . $throwable->getLine();
    $di->getShared('logger')->critical($message);
    fwrite(STDERR, $message. PHP_EOL);
    exit(1);
} catch (\Exception $exception) {
    $message = $exception->getCode() . ' : ' . $exception->getMessage() . ', '. $exception->getFile() . ' : line ' . $exception->getLine();
    $di->getShared('logger')->critical($message);
    fwrite(STDERR, $message. PHP_EOL);
    exit(1);
}