<?php
namespace App\factories;

use App\Config\DBPersistancy;
use App\Controllers\UserController;
use App\Repositories\UserPGRepository;

class UserControllerFactory
{
    public function __construct() { }

    public function createPGController(): UserController
    {
        $repository = new UserPGRepository();
        $controller = new UserController($repository);

        return $controller;
    }
}
