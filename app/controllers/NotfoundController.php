<?php

namespace Monkey\Controllers;
/**
 * @RoutePrefix('/404')
 */
class NotfoundController extends ControllerBase
{
    /**
     * @Get(
     *     '/'
     * )
     */
    public function indexAction()
    {
        $this->response->setStatusCode(404, 'Not Found');
        $this->response->setContent("not found");
        $this->response->send();
        exit();
    }

}
