<?php
namespace Monkey\Collections;

use Phalcon\Mvc\MongoCollection;

class UserCollection extends MongoCollection
{
    public $name;
    public $email;
    public $token;

    public function getSource()
    {
        return 'users';
    }
}