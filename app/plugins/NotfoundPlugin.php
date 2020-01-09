<?php
namespace Monkey\Plugins;

use Monkey\Controllers\ErrorsController;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Dispatcher\Exception as DispatcherException;

class NotfoundPlugin extends Plugin
{
    public function beforeException(Event $event, Dispatcher $dispatcher, \Exception $exception)
    {
        $uri = $this->request->getURI();
        if (\substr($uri, -1) == "/") {
            $uri = \substr($uri, 0, -1);
            header("Location: ".$uri);
            return;
        }
        $logger = $this->getDI()->getShared('logger');
        $logger->error($exception->getMessage() . PHP_EOL . $exception->getTraceAsString());
        if ($exception instanceof DispatcherException) {
            switch ($exception->getCode()) {
                case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                    $dispatcher->forward(
                        array(
                            'namespace' => 'Monkey\Controllers',
                            'controller' => 'Errors',
                            'action'     => 'code',
                            'params'     => [ErrorsController::CODE_NOT_FOUND],
                        )
                    );
                    return false;
            }
        }


        $dispatcher->forward(
            [
                'namespace' => 'Monkey\Controllers',
                'controller' => 'Errors',
                'action'     => 'code',
                'params'     => [ErrorsController::CODE_SERVER_ERROR],
            ]
        );
        return false;
    }
}