<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/customer_rep/{employee_id}/orders', function (Request $request, Response $response, array $args) {
    //TODO: Implement this endpoint
});

$app->get('customer_rep/{employee_id}/orders/{order_number}',function (Request $request, Response $response, array $args) {
    //TODO: Implement this endpoint
});

$app->put('customer_rep/{employee_id}/orders/{order_number}',function (Request $request, Response $response, array $args) {
    //TODO: Implement this endpoint
});

$app->get('customer_rep/{employee_id}/shipments',function (Request $request, Response $response, array $args) {
    //TODO: Implement this endpoint
});

$app->get('customers/{customer_id}/orders',function (Request $request, Response $response, array $args) {
    //TODO: Implement this endpoint
});

$app->post('customers/{customer_id}/orders/{order_number}',function (Request $request, Response $response, array $args) {
    //TODO: Implement this endpoint
});

$app->delete('customers/{customer_id}/orders/{order_number}',function (Request $request, Response $response, array $args) {
    //TODO: Implement this endpoint
});

$app->patch('customers/{customer_id}/orders/{order_number}',function (Request $request, Response $response, array $args) {
    //TODO: Implement this endpoint
});

$app->get('customers/summary',function (Request $request, Response $response, array $args) {
    //TODO: Implement this endpoint
});

$app->get('transporters/shipments',function (Request $request, Response $response, array $args) {
    //TODO: Implement this endpoint
});

$app->patch('transporters/shipments/{shipment_number}',function (Request $request, Response $response, array $args) {
    //TODO: Implement this endpoint
});

$app->get('public/skis',function (Request $request, Response $response, array $args) {
    //TODO: Implement this endpoint
});

$app->run();
