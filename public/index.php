<?php

use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require_once '../src/database/Database.php';
require_once '../src/database/dbCredentials.php';
require_once '../src/endpoints/customer.php';
require_once '../src/endpoints/customerRep.php';
require_once '../src/endpoints/transporters.php';
require_once '../src/endpoints/public.php';
require_once '../src/constants.php';

header('Content-Type: application/json');
$container = new Container();

AppFactory::setContainer($container);
$app = AppFactory::create();

$container->set('PDO', function () {
    return new Database();
});

/**
 * Fetching all orders of which a given employee is responsible for.
 */
$app->get('/customer_rep/{employee_id}/orders', function (Request $request, Response $response, array $args) {
    $employeeID = $args['employee_id'];
    $token      = $request->getHeaderLine('token');

    $db = $this->get('PDO');

    if ($db->isAuthorized($token)) {

        $dbInstance = $db->getDB();

        $res = fetchOrders($dbInstance, $employeeID);

        $response->getBody()->write(json_encode($res));
        return $response;
    } else {
        $response->getBody()->write(UNAUTHORIZED_TEXT);
        return $response->withStatus(HTTP_UNAUTHORIZED);
    }


});

/**
 * Changing the state of an order from new to open
 */
$app->put('/customer_rep/{employee_id}/orders/{order_number}', function (Request $request, Response $response, array $args) {
    $employeeID = $args['employee_id'];
    $orderNumber = $args['order_number'];
    $token      = $request->getHeaderLine('token');

    $db = $this->get('PDO');
    if ($db->isAuthorized($token)) {
        $dbInstance = $db->getDB();

        $res = updateOrderState($dbInstance, $employeeID, $orderNumber);

        $response->getBody()->write($res['body']);
        return $response->withStatus($res['status']);
    } else {
        $response->getBody()->write(UNAUTHORIZED_TEXT);
        return $response->withStatus(HTTP_UNAUTHORIZED);
    }
});

/**
 * Changing the state of an order from open to skies available when it should be filled up with skies
 */
$app->get('/customer_rep/{employee_id}/orders/{order_number}', function (Request $request, Response $response, array $args) {
    //TODO: Implement this endpoint
});

/**
 * Create a shipment request when an order has been filled
 */
$app->post('/customer_rep/{employee_id}/shipments', function (Request $request, Response $response, array $args) {
    $employeeID = $args['employee_id'];
    $token      = $request->getHeaderLine('token');
    $body = $request->getParsedBody();

    $db = $this->get('PDO');
    if ($db->isAuthorized($token)) {
        $dbInstance = $db->getDB();

        $res = createShipment($dbInstance, $employeeID, $body);

        $response->getBody()->write($res['body']);
        return $response->withStatus($res['status']);
    } else {
        $response->withStatus(HTTP_UNAUTHORIZED);
        $response->getBody()->write("User not authorized");
    }
});

/**
 * Retrieve a list of orders a given customer has made
 * TODO: Implement the optional since filter.
 */
$app->get('/customers/{customer_id}/orders', function (Request $request, Response $response, array $args) {
    $db = $this->get('PDO');
    $dbInstance = $db->getDB();

    $customerID = $args['customer_id'];
    $res = getOrdersForCustomer($dbInstance, $customerID);

    $response->getBody()->write(json_encode($res));
    return $response;
});

/**
 * Place a new order.
 */
$app->post('/customers/{customer_id}/orders', function (Request $request, Response $response, array $args) {
    $params = $request->getParsedBody();
    $response->getBody()->write(json_encode($params));

    $db = $this->get('PDO');
    $dbInstance = $db->getDB();

    $res = createNewOrder($dbInstance, $params);

    $response->getBody()->write(json_encode($res));
    return $response;
});

/**
 * Cancel an order
 */
$app->delete('/customers/{customer_id}/orders/{order_number}', function (Request $request, Response $response, array $args) {
    //TODO: Implement this endpoint
});

/**
 * Request that an order being split; on such a request, the unfilled items in the
 * order is moved to a new order (set in the open state), and the existing order is
 * changed from the skies available to ready for shipment state.
 */
$app->patch('/customers/{customer_id}/orders/{order_number}', function (Request $request, Response $response, array $args) {
    //TODO: Implement this endpoint
});

/**
 * Retrieve a four-week production plan summary showing the total number of skies being planned for the period.
 */
$app->get('/customers/summary', function (Request $request, Response $response, array $args) {
    $db = $this->get('PDO');
    $dbInstance = $db->getDB();

    $res = getProductionPlan($dbInstance);

    $response->getBody()->write(json_encode($res));
    return $response;
});

/**
 * Retrieve information about orders being ready for shipment
 */
$app->get('/transporters/shipments', function (Request $request, Response $response, array $args) {
    $db = $this->get('PDO');
    $dbInstance = $db->getDB();

    $res = getShipments($dbInstance);

    $response->getBody()->write(json_encode($res));
    return $response;

});

/**
 * Change the state of the shipment when it has been picked up.
 */
$app->put('/transporters/shipments/{shipment_number}', function (Request $request, Response $response, array $args) {
    $db = $this->get('PDO');
    $dbInstance = $db->getDB();
    $shipment_number = $args['shipment_number'];

    changeShipmentState($dbInstance, $shipment_number, 1);

    return $response->withStatus(204);
});

/**
 * The public endpoint should be open to anyone. Through this interface, any internet user should be able
 * to find information about the various types of skis. The user may optionally specify a filter based on model names.
 */
$app->get('/public/skis', function (Request $request, Response $response, array $args) {
    $db = $this->get('PDO');
    $dbInstance = $db->getDB();

    $res = getSkis($dbInstance);

    $response->getBody()->write(json_encode($res));
    return $response;

});

$app->run();
