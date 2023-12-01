<?php

declare(strict_types=1);

use App\factories\UserControllerFactory;
use Dotenv\Dotenv;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

require_once 'vendor/autoload.php';


$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$log = new Logger('app');
$formatter = new LineFormatter('{"level":"%level_name%","message":%message%,"timestamp":"%datetime%"}' . PHP_EOL, null, true, true);
$handler = new StreamHandler('logs/logfile.log', Logger::INFO);
$handler->setFormatter($formatter);
$log->pushHandler($handler);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: *");

$userControllerFactory = new UserControllerFactory();
$userController= $userControllerFactory->createPGController();

function saveLog($log, $data, $method, $path, $status) {
    if($status < 200 || $status >= 300) {
        $error = $data['data']['error'] ?? '';
        $log->error(json_encode(['body' => ['data' => $error], 'method' => $method, 'path' => $path, 'error' => $status]));
    } else {
        $log->info(json_encode(['body' => ['data' => $data['data']],  'method' => $method, 'path' => $path]));
    }
}

Flight::route("POST /users", function () use ($userController, $log) {
    $userData = Flight::request()->data->getData();
    $res = $userController->create($userData);

    saveLog($log, $res, 'POST', '/users', $res['status']);

    Flight::json($res["data"], $res["status"]);
});

Flight::route("GET /users/@id", function ($id) use ($userController, $log) {
    $id = (int) $id;
    $res = $userController->getById($id);

    saveLog($log, $res, 'GET', '/users/@id', $res['status']);

    Flight::json($res["data"], $res["status"]);
});

Flight::route("GET /users", function () use ($userController, $log) {
    $res = $userController->getAll();

    saveLog($log, $res, 'GET', '/users', $res['status']);

    Flight::json($res["data"], $res["status"]);
});

Flight::route("PUT /users/@id", function ($id) use ($userController, $log) {
    $id = (int) $id;
    $userData = Flight::request()->data->getData();
    $res = $userController->update($id, $userData);

    saveLog($log, $res, 'PUT', '/users/@id', $res['status']);

    Flight::json($res["data"], $res["status"]);
});

Flight::route("DELETE /users/@id", function ($id) use ($userController, $log) {
    $id = (int) $id;
    $res = $userController->delete($id);

    saveLog($log, $res, 'DELETE', '/users/@id', $res['status']);

    Flight::json($res["data"], $res["status"]);
});

Flight::start();
