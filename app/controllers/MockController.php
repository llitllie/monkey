<?php
namespace Monkey\Controllers;

use Monkey\Collections\MocksCollection;
use Monkey\Collections\UserCollection;

/**
 * @RoutePrefix('/mock')
 */
class MockController extends ControllerBase
{
    /**
     * @Get(
     *     '/'
     * )
     */
    public function indexAction()
    {
        $this->view->status = MocksCollection::getStatusCodeAndMessage();
        $this->view->contentType = MocksCollection::getContentType();
        //$this->view->charset = MocksCollection::getChartset();
        $this->view->curl = 'curl '.$this->request->getScheme().'://'.$this->request->getHttpHost().'/api/mock/5e0eb424a6427b575224cf32';
    }
}
