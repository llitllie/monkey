<?php
namespace Monkey\Plugins;

use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;

class LoggerPlugin extends Plugin
{
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        $logger = $this->getDI()->getShared('logger');
        $requestId = $this->request->getHeader('X-Request-Id');
        $logger->setRequestId($requestId);
        $this->response->setHeader('X-Request-Id', $logger->getRequestId());
        $uri = $this->request->getURI();
        $method = $this->request->getMethod();
        $rawBody = $this->request->getRawBody();
        $isJson = empty($rawBody) ? false : true;
        $data = $isJson ? $rawBody : json_encode($_REQUEST);
        $logger->info('request->ip->'.$this->request->getClientAddress());
        $logger->info('request->uri->'.$uri);
        $logger->info('request->method->'.$method);
        //do not log password
        if (!strpos($uri, 'sessions')) {
            $logger->info('request->parameters->'.$data);
        } 
    }
    public function afterExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        $logger = $this->getDI()->getShared('logger');;
        $logger->info('response->'.$this->response->getContent());
    }
    public function beforeException(Event $event, Dispatcher $dispatcher)
    {
        
    }
    public function beforeNotFoundAction(Event $event, Dispatcher $dispatcher)
    {
        
    }
}