<?php
namespace Monkey\Controllers;

use Monkey\Collections\MocksCollection;
use Monkey\Collections\UserCollection;
use MongoDB\BSON\ObjectId;

/**
 * @RoutePrefix('/')
 */
class IndexController extends ControllerBase
{
    /**
     * @Get(
     *     '/'
     * )
     */
    public function indexAction()
    {
        $users = new UserCollection();
        $users = $users->findById("5e0477eaea3f45dbc7c2e19a");
        //var_dump($users->findFirst()->toArray();
        //echo json_encode($users->findById("5e0477eaea3f45dbc7c2e19a"));
        //$users->name = "Kong";
        //$users->email = "kong@monkey.com";
        //$users->token = "balabalabalala";
        //$result = $users->save();
        //echo json_encode($users);
        //MongoDB\BSON\ObjectId 
        $objectId = $users->getId();
        var_dump($objectId);
        //__toString
        echo $objectId.PHP_EOL;
        //exit();
    }
}
