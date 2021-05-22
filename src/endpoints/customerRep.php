<?php

/**
 * @param PDO $dbInstance
 * @param mixed $employeeID
 * @return array
 */
function fetchOrders(PDO $dbInstance, mixed $employeeID): array
{

    $res = array();
    // We use SELECT * because we have created a custom view for this endpoint
    $query = "SELECT * FROM employee_orders
                WHERE employee_number = :eid";
    $stmt = $dbInstance->prepare($query);
    $stmt->bindValue(":eid", $employeeID);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $res[$row['order_number']] = $row;
        $sub_query =   "SELECT sio.order_number,`ski_id`, `quantity` 
                        FROM `skiis_in_order` AS sio
                        INNER JOIN `orders` AS o ON sio.order_number = o.order_number
                        WHERE sio.order_number = :order_num";
        $sub_stmt = $dbInstance->prepare($sub_query);
        $sub_stmt->bindValue(":order_num",$row['order_number']);
        $sub_stmt->execute();
        while ($sub_row = $sub_stmt->fetch(PDO::FETCH_ASSOC)) {
            $res[$row['order_number']]['skiis_in_order'] = $sub_row;
        }
    }
    return $res;
}


/**
 * @param PDO $dbInstance
 * @param mixed $employeeID
 * @param mixed $orderNumber
 * @param int $state New state of the order.
 * @return array
 */
function updateOrderState(PDO $dbInstance, mixed $employeeID, mixed $orderNumber, int $state): array
{
    $dbInstance->beginTransaction();


    $res = array();

    // Check if the order is in the previous state, and only update if it is.
    // We use SELECT * because we have created a custom view for this endpoint
    $query = "SELECT * FROM employee_orders
                WHERE employee_number = :eid AND order_number = :onb AND order_state = :state";
    $stmt = $dbInstance->prepare($query);
    $stmt->bindValue(":eid", $employeeID);
    $stmt->bindValue(":onb", $orderNumber);
    $stmt->bindValue(":state", $state - 1);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $res[] = $row;
    }

    // If there is not exactly ONE order matched by the above statement, then something is wrong.
    if (count($res) != 1) {
        $dbInstance->rollback();
        $data['body'] = "The order is either not associated with this employee or it does not exits";
        $data['status'] = 400;
        return $data;
    }

    $query = "UPDATE orders 
                SET orders.order_state = :state
                WHERE orders.order_number = :onb";
    $stmt = $dbInstance->prepare($query);
    $stmt->bindValue(":onb", $orderNumber);
    $stmt->bindValue(":state", $state);
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
    // We use SELECT * because we have created a custom view for this endpoint
    $query = "SELECT * FROM employee_orders
              WHERE employee_number = :eid AND order_number = :onb AND order_state = 3";
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