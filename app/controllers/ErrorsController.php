<?php

namespace Monkey\Controllers;

/**
 * @RoutePrefix('/errors')
 */
class ErrorsController extends ControllerBase
{
    const CODE_SUCCESS         = 200;
    const CODE_BAD_REQUEST     = 400;
    const CODE_UNAUTHORIZED    = 401;
    const CODE_FORBIDDEN       = 403;
    const CODE_NOT_FOUND       = 404;
    const CODE_REQUEST_TIMEOUT = 408;
    const CODE_TOO_MANY_REQUESTS = 429;
    const CODE_SERVER_ERROR    = 500;
    const CODE_SERVER_UNAVAILABLE= 502;


    const SUCCESS_RESPONSE = 0;
    const ERROR_UNKNOWN = 10000;
    const ERROR_MODEL_QUERY_FAILED = 10001;
    const ERROR_MODEL_ADD_FAILED = 10002;
    const ERROR_MODEL_UPDATE_FAILED = 10003;
    const ERROR_MODEL_DELETE_FAILED = 10004;
    const ERROR_MODEL_RECORD_NOT_FOUND = 10005;
    const ERROR_MODEL_RECORD_DUPLICATE = 10006;
    const ERROR_INPUT_INVALID = 90001;
    
    public static $ERROR_MESSAGE= array(
        self::CODE_SUCCESS => 'OK',
        self::CODE_BAD_REQUEST => 'Bad Request',
        self::CODE_UNAUTHORIZED => 'Unauthorized',
        self::CODE_FORBIDDEN => 'Forbidden',
        self::CODE_NOT_FOUND => 'Not Found',
        self::CODE_REQUEST_TIMEOUT => 'Request Timeout',
        self::CODE_TOO_MANY_REQUESTS => 'Too Many Requests',
        self::CODE_SERVER_ERROR => 'Internal Server Error',
        self::CODE_SERVER_UNAVAILABLE => 'Service Unavailable',
        self::SUCCESS_RESPONSE => '%s',
        self::ERROR_UNKNOWN => 'unknown error.',
        self::ERROR_MODEL_QUERY_FAILED => 'model query failed: %s',
        self::ERROR_MODEL_ADD_FAILED => 'model add failed: %s',
        self::ERROR_MODEL_UPDATE_FAILED => 'model update failed: %s',
        self::ERROR_MODEL_DELETE_FAILED => 'model delete failed: %s',
        self::ERROR_MODEL_RECORD_NOT_FOUND => '%s model record id %s no found.',
        self::ERROR_MODEL_RECORD_DUPLICATE => 'duplicate record %s ',
        self::ERROR_INPUT_INVALID => 'invalid input: %s',
    );
    /**
     * @Get(
     *     '/'
     * )
     */
    public function indexAction()
    {
        $message = self::getMessage(self::CODE_SUCCESS);
        $this->response->setStatusCode(self::CODE_SUCCESS, $message);
        $this->response->setContent($message);
        $this->response->send();
        exit();
    }

    /**
     * @Get(
     *     '/{code:[0-9]+}'
     * )
     */
    public function codeAction($code, $content = null)
    {
        $message = self::getMessage($code);
        if (empty($message)) {
            $code = self::CODE_BAD_REQUEST;
            $message = self::getMessage(self::CODE_BAD_REQUEST);
        }
        $content = $content ? $content : $message;
        $this->output($content, $code, $message);
    }

    /**
     * get the message by message id
     * @param number $msgid
     * @return string|null
     */
    public static function getMessage($code)
    {
        if( isset(self::$ERROR_MESSAGE[$code]) ) {
            return self::$ERROR_MESSAGE[$code];
        }
        return null;
    }

}
