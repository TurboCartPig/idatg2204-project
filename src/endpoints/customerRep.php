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

    $data['body'] = "Order state has been updated succesfully!";
    $data['status'] = 204;
    return $data;
}