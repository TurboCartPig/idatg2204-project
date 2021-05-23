<?php

use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use DBProject\Database\Database;
use DBProject\Constants;

require __DIR__ . '/../vendor/autoload.php';
require_once '../src/endpoints/customer.php';
require_once '../src/endpoints/customerRep.php';
require_once '../src/endpoints/transporters.php';
require_once '../src/endpoints/public.php';

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
        $response->getBody()->write(Constants::UNAUTHORIZED_TEXT);
        return $response->withStatus(Constants::HTTP_UNAUTHORIZED);
    }
});

/**
 * Changing the state of an order from new to open.
 */
$app->put('/customer_rep/{employee_id}/orders/{order_number}/open', function (Request $request, Response $response, array $args) {
    $employeeID = $args['employee_id'];
    $orderNumber = $args['order_number'];
    $token      = $request->getHeaderLine('token');

    $db = $this->get('PDO');
    if ($db->isAuthorized($token)) {
        $dbInstance = $db->getDB();

        $res = updateOrderState($dbInstance, $employeeID, $orderNumber, 2);

        $response->getBody()->write($res['body']);
        return $response->withStatus($res['status']);
    } else {
        $response->getBody()->write(Constants::UNAUTHORIZED_TEXT);
        return $response->withStatus(Constants::HTTP_UNAUTHORIZED);
    }
});

/**
 * Changing the state of an order from open to filled.
 */
$app->put('/customer_rep/{employee_id}/orders/{order_number}/filled', function (Request $request, Response $response, array $args) {
    $employeeID = $args['employee_id'];
    $orderNumber = $args['order_number'];
    $token      = $request->getHeaderLine('token');

    $db = $this->get('PDO');
    if ($db->isAuthorized($token)) {
        $dbInstance = $db->getDB();

        $res = updateOrderState($dbInstance, $employeeID, $orderNumber, 3);

        $response->getBody()->write($res['body']);
        return $response->withStatus($res['status']);
    } else {
        $response->getBody()->write(Constants::UNAUTHORIZED_TEXT);
        return $response->withStatus(Constants::HTTP_UNAUTHORIZED);
    }
});

/**
 * Create a shipment request if an order has been filled.
 */
$app->post('/customer_rep/{employee_id}/shipments', function (Request $request, Response $response, array $args) {
    $employeeID = $args['employee_id'];
    $token      = $request->getHeaderLine('token');
    $body       = $request->getBody();

    $db = $this->get('PDO');
    if ($db->isAuthorized($token)) {
        $dbInstance = $db->getDB();

        $res = createShipment($dbInstance, $employeeID, json_decode($body,true));

        $response->getBody()->write($res['body']);
        return $response->withStatus($res['status']);
    } else {
        $response->getBody()->write(Constants::UNAUTHORIZED_TEXT);
        return $response->withStatus(Constants::HTTP_UNAUTHORIZED);
    }
});

/**
 * Retrieve a list of orders a given customer has made
 * TODO: Implement the optional since filter.
 */
$app->get('/customers/{customer_id}/orders', function (Request $request, Response $response, array $args) {
    $customerID = $args['customer_id'];
    $token      = $request->getHeaderLine('token');

    $db = $this->get('PDO');
    if ($db->isAuthorized($token)) {

        $dbInstance = $db->getDB();

        $res = getOrdersForCustomer($dbInstance, $customerID);

        $response->getBody()->write(json_encode($res));
        return $response;
    } else {
        $response->getBody()->write(Constants::UNAUTHORIZED_TEXT);
        return $response->withStatus(Constants::HTTP_UNAUTHORIZED);
    }
});

/**
 * Place a new order.
 */
$app->post('/customers/orders', function (Request $request, Response $response, array $args) {
    $params = $request->getBody();
    $token  = $request->getHeaderLine('token');

    $db = $this->get('PDO');
    if ($db->isAuthorized($token)) {
        $dbInstance = $db->getDB();
        $res = createNewOrder($dbInstance, json_decode($params,true));

        return $response->withStatus(Constants::HTTP_OK);
    } else {
        $response->getBody()->write(Constants::UNAUTHORIZED_TEXT);
        return $response->withStatus(Constants::HTTP_UNAUTHORIZED);
    }

});

/**
 * Cancel an order.
 */
$app->delete('/customers/{customer_id}/orders/{order_number}', function (Request $request, Response $response, array $args) {
    $customer_id = $args['customer_id'];
    $order_number = $args['order_number'];
    $token = $request->getHeaderLine('token');

    $db = $this->get('PDO');
    if ($db->isAuthorized($token)) {
        $dbInstance = $db->getDB();

        try {
            deleteOrder($dbInstance, $customer_id, $order_number);
        } catch (RuntimeException $except) {
            $response->getBody()->write("Failed to cancel order. Check order number and customer id. Only the owner of an order can cancel it.");
            return $response->withStatus(Constants::HTTP_BAD_REQUEST);
        }

        return $response->withStatus(Constants::HTTP_NO_CONTENT);
    } else {
        $response->getBody()->write(Constants::UNAUTHORIZED_TEXT);
        return $response->withStatus(Constants::HTTP_UNAUTHORIZED);
    }
});

/**
 * Request that an order being split; on such a request, the unfilled items in the
 * order is moved to a new order (set in the open state), and the existing order is
 * changed from open to filled.
 */
$app->patch('/customers/{customer_id}/orders/{order_number}', function (Request $request, Response $response, array $args) {
    //TODO: Implement this endpoint
});

/**
 * Retrieve a four-week production plan summary showing the total number of skies being planned for the period.
 */
$app->get('/customers/summary', function (Request $request, Response $response, array $args) {
    $token = $request->getHeaderLine('token');

    $db = $this->get('PDO');
    if ($db->isAuthorized($token)) {
        $dbInstance = $db->getDB();

        $res = getProductionPlan($dbInstance);

        $response->getBody()->write(json_encode($res));
        return $response;
    } else {
        $response->getBody()->write(Constants::UNAUTHORIZED_TEXT);
        return $response->withStatus(Constants::HTTP_UNAUTHORIZED);
    }
});

/**
 * Retrieve information about shipments.
 */
$app->get('/transporters/shipments', function (Request $request, Response $response, array $args) {
    $token = $request->getHeaderLine('token');

    $db = $this->get('PDO');
    if ($db->isAuthorized($token)) {

        $dbInstance = $db->getDB();

        $res = getShipments($dbInstance);

        $response->getBody()->write(json_encode($res));
        return $response;
    } else {
        $response->getBody()->write(Constants::UNAUTHORIZED_TEXT);
        return $response->withStatus(Constants::HTTP_UNAUTHORIZED);
    }
});

/**
 * Change the state of the shipment when it has been picked up.
 */
$app->put('/transporters/shipments/{shipment_number}', function (Request $request, Response $response, array $args) {
    $token = $request->getHeaderLine('token');
    $shipment_number = $args['shipment_number'];

    $db = $this->get('PDO');
    if ($db->isAuthorized($token)) {
        $dbInstance = $db->getDB();

        changeShipmentState($dbInstance, $shipment_number, 1);

        return $response->withStatus(Constants::HTTP_NO_CONTENT);
    } else {
        $response->getBody()->write(Constants::UNAUTHORIZED_TEXT);
        return $response->withStatus(Constants::HTTP_UNAUTHORIZED);
    }
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
    return $response->withStatus(Constants::HTTP_OK);
});

$app->run();
