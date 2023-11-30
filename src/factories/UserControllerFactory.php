<?php
namespace App\Factories;

use App\Controllers\UserController;
use App\Repositories\UserPGRepository;

class UserControllerFactory
{
    public function createPGController(): UserController
    {
        $repository = new UserPGRepository();
        $controller = new UserController($repository);

        return $controller;
    }
}
