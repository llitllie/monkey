<?php
namespace Monkey\Collections;

use Phalcon\Mvc\MongoCollection;

class MocksCollection extends MongoCollection
{
    public $code;
    public $message;
    public $headers;
    public $body;
    public $lastUpdate;

    public function getSource()
    {
        return 'mocks';
    }

    public static function getHttpVesion()
    {
        return array(
            "HTTP/1.1",
            "HTTP/2.0",
            "HTTP/2"
        );
    }

    public static function getStatusMessageByCode($code)
    {
        $map = self::getStatusCodeAndMessage();
        if (isset($map[$code])) {
            return $map[$code];
        }
        return "unknown";
    }

    public static function getStatusCodeAndMessage()
    {
        return array(
            200 => "OK",
            201 => "Created",
            202 => "Accepted",
            204 => "No Content",
            301 => "Moved Permanently",
            302 => "Found",
            303 => "See Other",
            304 => "Not Modified",
            400 => "Bad Request",
            401 => "Unauthorized",
            402 => "Payment Required",
            403 => "Forbidden",
            404 => "Not Found",
            405 => "Method Not Allowed",
            406 => "Not Acceptable",
            408 => "Request Timeout",
            409 => "Conflict",
            411 => "Length Required",
            412 => "Precondition Failed ",
            413 => "Payload Too Large",
            414 => "URI Too Long",
            415 => "Unsupported Media Type",
            418 => "I'm a teapot",
            423 => "Locked",
            424 => "Failed Dependency",
            425 => "Too Early",
            426 => "Upgrade Required",
            428 => "Precondition Required",
            429 => "Too Many Requests ",
            431 => "Request Header Fields Too Large",
            451 => "Unavailable For Legal Reasons",
            500 => "Internal Server Error",
            501 => "Not Implemented",
            502 => "Bad Gateway",
            503 => "Service Unavailable",
            504 => "Gateway Timeou",
            505 => "HTTP Version Not Supported",
            506 => "Variant Also Negotiates ",
            508 => "Loop Detected",
            510 => "Not Extended",
            511 => "Network Authentication Required"
        );
    }

    public static function getContentType()
    {
        return array(
            "application/json",
            "application/vnd.api+json",
            "application/javascript",
            "application/xml",
            "text/plain",
            "text/html"
        );
    }

    public static function getChartset()
    {
        return array(
            "UTF-8"
        );
    }
}