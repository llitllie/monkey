<?php
namespace Sim;

use Phalcon\Logger\Adapter\File;
use Phalcon\Logger\Formatter\Line;
use Phalcon\Security\Random;

class Logger extends File
{
    private $_requestId = null;

    public function setRequestId($id)
    {
        $this->_requestId = $id;
    }

    public function getRequestId()
    {
        if (empty($this->_requestId)) {
            $this->_requestId = (new Random())->uuid();
        }
        return $this->_requestId;
    }

    public function resetRequestId()
    {
        $id= (new Random())->uuid();
        $this->setRequestId($id);
    }

    public function getFormatter()
    {
        if (!is_object($this->_formatter)) {
            $this->_formatter = new Line('[%date%][%type%]%message%');
        }
        return $this->_formatter;
    }

    public function logInternal($message, $type, $timestamp, $context = null)
    {
        $message = sprintf('[%s] %s', $this->getRequestId(), $message);
        parent::logInternal($message, $type, $timestamp, $context);
    }
}
