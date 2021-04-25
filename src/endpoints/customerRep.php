<?php

/**
 * @param PDO $dbInstance
 * @param mixed $employeeID
 * @return array
 */
function fetchOrders(PDO $dbInstance, mixed $employeeID): array
{
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
    return $res;
}


/**
 * @param PDO $dbInstance
 * @param mixed $employeeID
 * @param mixed $orderNumber
 * @return array
 */
function updateOrderState(PDO $dbInstance, mixed $employeeID, mixed $orderNumber): array
{
    $dbInstance->beginTransaction();
    $res = array();
    $query = "SELECT * FROM orders 
                INNER JOIN employee ON employee.number = orders.customer_rep
                WHERE employee.number = :eid AND orders.order_number = :onb AND orders.order_state = 'In production'";
    $stmt = $dbInstance->prepare($query);
    $stmt->bindValue(":eid", $employeeID);
    $stmt->bindValue(":onb", $orderNumber);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $res[] = $row;
    }

    // If there is not exactly ONE order matched by the above statement, then something is wrong.
    if (count($res) != 1) {
        $dbInstance->commit();
        $data['body'] = "The order is either not associated with this employee or it does not exits";
        $data['status'] = 400;
        return $data;
    }

    $query = "UPDATE orders 
                SET orders.order_state = 'Ready for shipping'
                WHERE orders.order_number = :onb";
    $stmt = $dbInstance->prepare($query);
    $stmt->bindValue(":onb", $orderNumber);
    $stmt->execute();

    $dbInstance->commit();

    $data['body'] = "";
    $data['status'] = 204;
    return $data;
}


/**
 * @param PDO $dbInstance
 * @param mixed $employeeID
 * @param mixed $body
 * @return array
 */
function createShipment(PDO $dbInstance, mixed $employeeID, mixed $body)
{
    $dbInstance->beginTransaction();

    $res = array();
    $query = "SELECT * FROM orders
              INNER JOIN employee ON employee.number = orders.customer_id
              WHERE employee.number = :eid AND orders.order_number = :onb AND orders.order_state = 'Ready for shipping'";
    $stmt = $dbInstance->prepare($query);
    $stmt->bindValue(":eid", $employeeID);
    $stmt->bindValue(":onb", $body['order_number']);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $res[] = $row;
    }

    // If there is not exactly ONE order matched by the above statement, then something is wrong.
    if (count($res) != 1) {
        $dbInstance->commit();
        $data['body'] = "The order is either not associated with this employee, it does not exits, or it is not ready for shipping";
        $data['status'] = 400;
        return $data;
    }


    // FIXME: Do not create a shipment for orders that already have shipments associated with them.
    // TODO: Do not get ids from the user.
    $query = "INSERT INTO shipment (address_id, pickup_date, shipment_state, order_number, transporter_id, driver_id)
              VALUES (:address_id, :pickup_date, 2, :order_number, :transporter_id, :driver_id)";
    $stmt = $dbInstance->prepare($query);
    $stmt->bindValue(":address_id", $body['address_id']);
    $stmt->bindValue(":pickup_date", $body['pickup_date']);
    $stmt->bindValue(":order_number", $body['order_number']);
    $stmt->bindValue(":transporter_id", $body['transporter_id']);
    $stmt->bindValue(":driver_id", $body['driver_id']);

    try {
        $stmt->execute();
        $dbInstance->commit();
    } catch (PDOException $excpet) {
        $data['body'] = "Exception occurred in the database";
        $data['status'] = 400;
        return $data;
    }

    $data['body'] = "";
    $data['status'] = 204;
    return $data;
}