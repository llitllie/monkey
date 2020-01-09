<?php
use Phalcon\Mvc\Router\Annotations as RouterAnnotations;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Events\Manager as EventsManager;
use Monkey\Plugins\LoggerPlugin;
use Monkey\Plugins\NotfoundPlugin;

$router = $di->getRouter();

// Define your routes here
// Define your routes here
$di['router'] = function () {
    // Use the annotations router. We're passing false as we don't want the router to add its default patterns
    $router = new RouterAnnotations(false);
    
    $router->addResource('Monkey\Controllers\Index', '/');
    $router->addResource('Monkey\Controllers\Errors', '/errors');
    $router->addResource('Monkey\Controllers\Notfound', '/404');
    $router->addResource('Monkey\Controllers\Forbidden', '/403');
    $router->addResource('Monkey\Controllers\Mock', '/mock');
    $router->addResource('Monkey\Controllers\Api\Mock', '/api/mock');
    
    return $router;
};

$router->handle();

$di->set(
    'dispatcher',
    function () {
        $eventsManager = new EventsManager();
        $eventsManager->attach(
            "dispatch:beforeException",
            new NotfoundPlugin()
        );
        $eventsManager->attach(
            "dispatch:beforeExecuteRoute",
            new LoggerPlugin()
        );
        $eventsManager->attach(
             "dispatch:afterExecuteRoute",
             new LoggerPlugin()
        );
        /*$eventsManager->attach(
            "dispatch:beforeExecuteRoute",
            new AntiSpiderPlugin()
        );*/
        $dispatcher = new Dispatcher();
        $dispatcher->setEventsManager($eventsManager);
        //$dispatcher->setDefaultNamespace('Peg\Controllers');
        //$dispatcher->setDefaultController('Notfound');
        
        return $dispatcher;
    }
);