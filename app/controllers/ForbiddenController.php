<?php

namespace Monkey\Controllers;
/**
 * @RoutePrefix('/403')
 */
class ForbiddenController extends ControllerBase
{
    /**
     * @Get(
     *     '/'
     * )
     */
    public function indexAction()
    {
        $this->response->setStatusCode(403, 'Forbidden');
        $this->response->setContent("forbidden");
        $this->response->send();
        exit();
    }

}
