<?php

declare(strict_types=1);

use App\factories\UserControllerFactory;
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
    $userData = Flight::request()->data->getData();
    $res = $userController->create($userData);
    Flight::json($res["data"], $res["status"]);
});

Flight::route("GET /users/@id", function ($id) use ($userController) {
    $id = (int) $id;
    $res = $userController->getById($id);
    Flight::json($res["data"], $res["status"]);
});

Flight::route("GET /users", function () use ($userController) {
    $res = $userController->getAll();
    Flight::json($res["data"], $res["status"]);
});

Flight::route("PUT /users/@id", function ($id) use ($userController) {
    $id = (int) $id;
    $userData = Flight::request()->data->getData();
    $res = $userController->update($id, $userData);
    Flight::json($res["data"], $res["status"]);
});

Flight::route("DELETE /users/@id", function ($id) use ($userController) {
    $id = (int) $id;
    $res = $userController->delete($id);
    Flight::json($res["data"], $res["status"]);
});

Flight::start();
