<?php
namespace Monkey\Controllers\Api;

use Monkey\Collections\MocksCollection;
use MongoDB\BSON\ObjectId;
use Monkey\Controllers\ControllerBase;
use Monkey\Controllers\ErrorsController;
use MongoDB\Driver\Exception\InvalidArgumentException;
use MongoDB\BSON\UTCDateTime;

/**
 * @RoutePrefix('/api/mock')
 */
class MockController extends ControllerBase
{
    const MAX_DELAY = 30;
    /**
     * @Route(
     *     '/{id}',
     *     methods={'GET', 'POST', 'PUT', 'DELETE', 'HEAD', 'OPTIONS', 'PATCH'}
     * )
     */
    public function findAction($id)
    {
        try {
            $objectId = new ObjectId($id);
            $mocks = new MocksCollection();
            $mock = $mocks->findById($objectId);
            if ($mock) {
                $delay = $this->request->getQuery('_delay', 'int', '0');
                if ($delay && ($delay <= self::MAX_DELAY)) {
                    sleep($delay);
                }
                //$this->response->setHeader('Access-Control-Allow-Origin', '*');
                $this->output($mock->body, $mock->code, $mock->message, $mock->headers);
            } else {
                $this->output("$id not found", ErrorsController::CODE_NOT_FOUND);
            }
        } catch (InvalidArgumentException $e) {
            $this->output("$id is invalid id", ErrorsController::CODE_BAD_REQUEST);
        } catch(\Exception $e) {
            $this->log($e->getMessage());
            $this->output("unexpected error", ErrorsController::CODE_SERVER_ERROR);
        }
    }

    private function _getRequestArray()
    {
        $data = $this->request->getJsonRawBody(true);
        if(empty($data)){
            $data = [];
            $request = $this->request;
            $data['token'] = $request->getPost('token') ?  $request->getPost('token') : $request->getPut('token');
            $data['code'] = $request->getPost('code') ?  $request->getPost('code') : $request->getPut('code');
            $data['message'] = $request->getPost('message') ?  $request->getPost('message') : $request->getPut('message');
            $data['headers'] = $request->getPost('headers') ?  $request->getPost('headers') : $request->getPut('headers');
            $data['body'] = $request->getPost('body') ?  $request->getPost('body') : $request->getPut('body');
            
            $data = \array_filter($data, function($item){ return !is_null($item); });
        }
        $data['tokenKey'] = isset($data['tokenKey']) ? $data['tokenKey'] : '';
        $data['token'] = isset($data['token']) ? $data['token'] : '';
        $data['code'] = isset($data['code']) ? intval($data['code']) : ErrorsController::CODE_SUCCESS;
        $data['message'] = isset($data['message']) ? $data['message'] : MocksCollection::getStatusMessageByCode($data['code']);
        $data['body'] = isset($data['body']) ? $data['body'] : '';
        $data['headers'] = isset($data['headers']) ? $data['headers'] : ['Content-Type' => 'application/json; charset=UTF-8']; 
        $data['lastUpdate'] = new UTCDateTime(new \DateTime);
        return $data;
    }

    /**
     * @Post(
     *     '/'
     * )
     */
    public function createAction()
    {
        $data = $this->_getRequestArray();
        //TODO verify this token, limit it one by one
        if (!$this->security->checkToken($data['tokenKey'], $data['token'])) {
            $this->error(ErrorsController::ERROR_INPUT_INVALID, "Please create mocks one by one, refresh your browser to continue");
        }
        if (empty($data['code'])) {
            $this->error(ErrorsController::ERROR_INPUT_INVALID, "Reponse status code is required");
        }
        if (empty($data['message'])) {
            $this->error(ErrorsController::ERROR_INPUT_INVALID, "Reponse status code is invalid");
        }
        if (!isset($data['headers']['Content-Type'])) {
            $this->error(ErrorsController::ERROR_INPUT_INVALID, "Reponse Content-Type is required");
        }
        if (!isset($data['body'])) {
            $this->error(ErrorsController::ERROR_INPUT_INVALID, "Reposne body is required");
        }
        $mock = new MocksCollection();
        $mock->code = $data['code'];
        $mock->message = $data['message'];
        $mock->headers = $data['headers'];
        $mock->body = $data['body'];
        $mock->lastUpdate = $data['lastUpdate'];
        $result = $mock->save();
        if ($result) {
            $this->success("create mock success", ['id' => (string)$mock->getId(), 'token' => $this->security->getToken(), 'key' => $this->security->getTokenKey()]);
        } else {
            $this->error(ErrorsController::ERROR_MODEL_ADD_FAILED, "mocks");
        }
    }

}

