<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/customer_rep/{employee_id}/orders', function (Request $request, Response $response, array $args) {

});

$app->get('customer_rep/{employee_id}/orders/{order_number}',function (Request $request, Response $response, array $args) {

});

$app->put('customer_rep/{employee_id}/orders/{order_number}',function (Request $request, Response $response, array $args) {

});


$app->get('customer_rep/{employee_id}/shipments',function (Request $request, Response $response, array $args) {

});

$app->get('customers/{customer_id}/orders',function (Request $request, Response $response, array $args) {

});

$app->post('customers/{customer_id}/orders/{order_number}',function (Request $request, Response $response, array $args) {

});

$app->delete('customers/{customer_id}/orders/{order_number}',function (Request $request, Response $response, array $args) {

});

$app->patch('customers/{customer_id}/orders/{order_number}',function (Request $request, Response $response, array $args) {

});

$app->get('customers/summary',function (Request $request, Response $response, array $args) {

});

$app->get('transporters/shipments',function (Request $request, Response $response, array $args) {

});

$app->patch('transporters/shipments/{shipment_number}',function (Request $request, Response $response, array $args) {

});


$app->get('public/skis',function (Request $request, Response $response, array $args) {

});

$app->run();
