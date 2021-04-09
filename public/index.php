<?php

use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require_once '../src/database/Database.php';
require_once '../src/database/dbCredentials.php';

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
$app->get('/customer_rep/{employee_id}/orders', function (Request $request, Response $response, array $args) use (&$db) {
    $employeeID = $args['employee_id'];

    $db = $this->get('PDO');
    $dbInstance = $db->getDB();

    $res = array();
    $query = "SELECT * FROM orders 
                INNER JOIN employee ON employee.number = orders.customer_rep
                WHERE employee.number = :eid";
    $stmt = $dbInstance->prepare($query);
    $stmt->bindValue(":eid", $employeeID);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $res[] = $row;
    }

    $response->getBody()->write(json_encode($res));
    return $response;
});

/**
 * Changing the state of an order from new to open
 */
$app->put('/customer_rep/{employee_id}/orders/{order_number}', function (Request $request, Response $response, array $args) {
    $employeeID = $args['employee_id'];
    $order_number = $args['order_number'];

    $db = $this->get('PDO');
    $dbInstance = $db->getDB();

    $res = array();
    $query = "SELECT * FROM orders 
                INNER JOIN employee ON employee.number = orders.customer_rep
                WHERE employee.number = :eid AND orders.order_number = :onb AND orders.order_state = 'Received'";
    $stmt = $dbInstance->prepare($query);
    $stmt->bindValue(":eid", $employeeID);
    $stmt->bindValue(":onb", $order_number);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $res[] = $row;
    }

    // If there is not exactly ONE order matched by the above statement, then something is wrong.
    if (count($res) != 1) {
        $response->getBody()->write("The order is either not associated with this employee or it does not exits");
        $response->withStatus(400);
        return $response;
    }

    $query = "UPDATE orders 
                SET orders.order_state = 'Open'
                WHERE orders.order_number = :onb";
    $stmt = $dbInstance->prepare($query);
    $stmt->bindValue(":onb", $order_number);
    $stmt->execute();

    $response->withStatus(204);
    return $response;

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
$app->get('/customer_rep/{employee_id}/shipments', function (Request $request, Response $response, array $args) {
    //TODO: Implement this endpoint
});

/**
 * Retrieve a list of orders a given customer has made
 * TODO: Implement the optional since filter.
 */
$app->get('/customers/{customer_id}/orders', function (Request $request, Response $response, array $args) {
    $customerID = $args['customer_id'];

    $db = $this->get('PDO');
    $dbInstance = $db->getDB();

    $res = array();
    $query = "SELECT * FROM orders 
                INNER JOIN customer ON customer.id = orders.customer_id
                WHERE :eid = orders.customer_id";
    $stmt = $dbInstance->prepare($query);
    $stmt->bindValue(":eid",$customerID);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $res[] = $row;
    }

    $response->getBody()->write(json_encode($res));
    return $response;
});

/**
 * Place a new order.
 */
$app->post('/customers/{customer_id}/orders', function (Request $request, Response $response, array $args) {
    $params = $request->getParsedBody();
    $response->getBody()->write(json_encode($params));

    $customer_id = $args['customer_id'];

    $db = $this->get('PDO');
    $dbInstance = $db->getDB();

    $res = array();
    $query = "INSERT INTO orders (total_price, customer_rep, order_state, customer_id)
              VALUES (:price,:cus_rep,'In production',:cid)";
    $stmt = $dbInstance->prepare($query);
    $stmt->bindValue(":price",$params['price']);
    $stmt->bindValue(":cus_rep",$params['customer_rep']);
    $stmt->bindValue(":cid",$customer_id);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $res[] = $row;
    }

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

    $res = array();
    $query = "SELECT * FROM production_plan";
    $stmt = $dbInstance->prepare($query);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $res[] = $row;
    }

    $response->getBody()->write(json_encode($res));
    return $response;
});

/**
 * Retrieve information about orders being ready for shipment
 */
$app->get('/transporters/shipments', function (Request $request, Response $response, array $args) {
    //TODO: Implement this endpoint
});

/**
 * Change the state of the shipment when it has been picked up.
 */
$app->patch('/transporters/shipments/{shipment_number}', function (Request $request, Response $response, array $args) {
    //TODO: Implement this endpoint
});

/**
 * The public endpoint should be open to anyone. Through this interface, any internet user should be able
 * to find information about the various types of skis. The user may optionally specify a filter based on model names. 
 */
$app->get('/public/skis', function (Request $request, Response $response, array $args) {
    //TODO: Implement this endpoint
});

$app->run();
