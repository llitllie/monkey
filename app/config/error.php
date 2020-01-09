<?php

register_shutdown_function(function() use ($di){
    $error = error_get_last();
    if ( $error["type"] == E_ERROR ){
        $di->getShared('logger')->critical($error["message"]." - ".$error["file"].":".$error["line"]);
        //throw new \Exception($error["message"].PHP_EOL.$error["file"].":".$error["line"], $error["type"]);
    }
    //exit();
});
set_error_handler(function($errno, $errstr, $errfile, $errline) use ($di) {
    $di->getShared('logger')->error($errstr." - ".$errfile.":".$errline);
    //throw new \Exception($errstr.PHP_EOL.$errfile.":".$errline, $errno);
    //exit();
});
/*
set_exception_handler(function($exception) use ($di) {
$di->getShared('logger')->critical($exception->getMessage()." - ".$exception->getFile().":".$exception->getLine());
});
 */
ini_set( "display_errors", "off" );
error_reporting( E_ALL );
