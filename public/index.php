<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use DI\Container;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require_once '../src/database/Database.php';
require_once '../src/database/dbCredentials.php';

$container = new Container();

AppFactory::setContainer($container);
$app = AppFactory::create();

$container->set('PDO',function () {
    return new Database();
});

$app->get('/customer_rep/{employee_id}/orders', function (Request $request, Response $response, array $args) use (&$db) {
    $db = $this->get('PDO');
    $dbInstance = $db->getDB();
    $res = array();
    $query = "SELECT * FROM customer";
    $stmt = $dbInstance->query($query);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $res[] = $row;
    }
    print_r($res);
    return $response;
});

$app->get('/customer_rep/{employee_id}/orders/{order_number}', function (Request $request, Response $response, array $args) {
    //TODO: Implement this endpoint
});

$app->put('/customer_rep/{employee_id}/orders/{order_number}', function (Request $request, Response $response, array $args) {
    //TODO: Implement this endpoint
});

$app->get('/customer_rep/{employee_id}/shipments', function (Request $request, Response $response, array $args) {
    //TODO: Implement this endpoint
});

$app->get('/customers/{customer_id}/orders', function (Request $request, Response $response, array $args) {
    //TODO: Implement this endpoint
});

$app->post('/customers/{customer_id}/orders/{order_number}', function (Request $request, Response $response, array $args) {
    //TODO: Implement this endpoint
});

$app->delete('/customers/{customer_id}/orders/{order_number}', function (Request $request, Response $response, array $args) {
    //TODO: Implement this endpoint
});

$app->patch('/customers/{customer_id}/orders/{order_number}', function (Request $request, Response $response, array $args) {
    //TODO: Implement this endpoint
});

$app->get('/customers/summary', function (Request $request, Response $response, array $args) {
    //TODO: Implement this endpoint
});

$app->get('/transporters/shipments', function (Request $request, Response $response, array $args) {
    //TODO: Implement this endpoint
});

$app->patch('/transporters/shipments/{shipment_number}', function (Request $request, Response $response, array $args) {
    //TODO: Implement this endpoint
});

$app->get('/public/skis', function (Request $request, Response $response, array $args) {
    //TODO: Implement this endpoint
});

$app->run();
