<?php

declare(strict_types=1);

use App\Factories\UserControllerFactory;
use Dotenv\Dotenv;

require_once 'vendor/autoload.php';


$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: *");

$userControllerFactory = new UserControllerFactory();
$userController= $userControllerFactory->createPGController();

Flight::route("POST /users", function () use ($userController) {
    $userController->create();
});

Flight::start();
