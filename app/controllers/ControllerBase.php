<?php

namespace Monkey\Controllers;

use Phalcon\Logger;
use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    public function output($mixed, $code = ErrorsController::CODE_SUCCESS, $message = "", $headers = null)
    {
        if(is_array($mixed) || is_object($mixed)){
            $this->response->setJsonContent($mixed);
        }
        else{
            if (!empty($headers)) {
                foreach($headers as $k => $v) {
                    $this->response->setHeader($k, $v);
                }
            }
            $this->response->setContent($mixed);
        }
        $message = empty($message) ? ErrorsController::getMessage($code) : $message;
        $this->response->setStatusCode($code, $message);
        $this->response->send();
        $this->log($this->response->getContent());
        exit();
    }
    public function success($message = '', $more= '')
    {
        $message = empty($message) ? 'operation success' : $message;
        $data = ['error' => ErrorsController::SUCCESS_RESPONSE, 'message' => $message, 'data' => $more];
        $this->output($data);
    }
    public function error($code, $more = '')
    {
        $message = ErrorsController::getMessage($code);
        if($message){
            if(!empty($more)){
                if(is_array($more)){
                    $message = vsprintf($message, $more);
                }
                else{
                    //object will throw error
                    $message = sprintf($message, $more);
                }
            }
            $data = ['error' => $code, 'message' => $message, 'data' => $more];
        }
        else{
            $data = ['error' => $code, 'message' => ErrorsController::getMessage(ErrorsController::ERROR_UNKNOWN), 'data' => $more];
        }
        $this->output($data);
    }

    public function log($content, $level = Logger::INFO)
    {
        $logger = $this->getDI()->getShared('logger');;
        $logger->log($level, $content);
    }
}