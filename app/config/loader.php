<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerNamespaces(
    [
        'Monkey\Controllers' => $config->application->controllersDir,
        'Monkey\Models'      => $config->application->modelsDir,
        'Monkey\Collections'      => $config->application->collectionsDir,
        'Monkey\Plugins'     => $config->application->pluginsDir,
        'Sim'             => $config->application->libraryDir.'Sim/',
        'Monkey\Jobs'        => $config->application->jobsDir,
        'Monkey\Tasks'        => $config->application->tasksDir,
        'Monkey\Services'    => $config->application->servicesDir,
        'Monkey\Components'  => $config->application->componentsDir
    ]
);

$loader->registerFiles([APP_PATH . '/vendor/autoload.php']);

$loader->register();
