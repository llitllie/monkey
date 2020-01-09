<?php

use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Sim\Logger;
use Phalcon\Mvc\Collection\Manager;
use Phalcon\Db\Adapter\MongoDB\Client;

/**
 * Shared configuration service
 */
$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});

/**
 * Setting up the view component
 */
$di->setShared('view', function () {
    $config = $this->getConfig();

    $view = new View();
    $view->setDI($this);
    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines([
        '.volt' => function ($view) {
            $config = $this->getConfig();

            $volt = new VoltEngine($view, $this);

            $volt->setOptions([
                'compiledPath' => $config->application->cacheDir,
                'compiledSeparator' => '_'
            ]);

            return $volt;
        },
        '.phtml' => PhpEngine::class

    ]);

    return $view;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () {
    $config = $this->getConfig();

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $params = [
        'host'     => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname'   => $config->database->dbname,
        'charset'  => $config->database->charset
    ];

    if ($config->database->adapter == 'Postgresql') {
        unset($params['charset']);
    }

    $connection = new $class($params);

    return $connection;
});


/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
});

/**
 * Register logger for common error and exception
 */

$di->setShared('logger', function () {
    $fileName = '/var/log/php/monkey.log';
    if(!is_file($fileName)){
        touch($fileName);
        chmod($fileName, 0777);
    }
    $logger = new Logger($fileName);
    return $logger;
});

// Initialise the mongo DB connection.
$di->setShared(
    'mongo',
    function () {
        /** @var \Phalcon\DiInterface $this */

        $config = $this->getShared('config');
        $username = getenv('MONGO_USERNAME') ? getenv('MONGO_USERNAME'): $config->mongo->username;
        $password = getenv('MONGO_PASSWORD') ? getenv('MONGO_PASSWORD'): $config->mongo->password;
        $host = getenv('MONGO_HOST') ? getenv('MONGO_HOST'): $config->mongo->host;
        $dbname = getenv('MONGO_DBNAME') ? getenv('MONGO_DBNAME'): $config->mongo->dbname;
        $host = empty($host) ? '127.0.0.1' : $host;

        if (!$username || !$password) {
            $dsn = 'mongodb://' . $host;
        } else {
            $dsn = sprintf(
                'mongodb://%s:%s@%s',
                $username,
                $password,
                $host
            );
        }

        $mongo = new Client($dsn);
        
        return $mongo->selectDatabase(
            $dbname
        );
    }
);

// Collection Manager is required for MongoDB
$di->setShared(
    'collectionManager',
    function () {
        return new Manager();
    }
);
