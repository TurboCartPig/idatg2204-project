<?php

/**
 * @param PDO $dbInstance
 * @param mixed $customerID
 * @return array
 */
function getOrdersForCustomer(PDO $dbInstance, mixed $customerID): array
{
    $res = array();
    $query = "SELECT order_number, parent_number, customer_id, customer_rep, total_price, order_state 
              FROM orders 
              WHERE :cid = orders.customer_id";
    $stmt = $dbInstance->prepare($query);
    $stmt->bindValue(":cid", $customerID);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $res[$row['order_number']] = $row;
        $sub_query =   "SELECT sio.order_number,`ski_id`, `quantity`, sio.`order_state` 
                        FROM `skis_in_order` AS sio
                        INNER JOIN `orders` AS o ON sio.order_number = o.order_number
                        WHERE sio.order_number = :order_num";
        $sub_stmt = $dbInstance->prepare($sub_query);
        $sub_stmt->bindValue(":order_num",$row['order_number']);
        $sub_stmt->execute();
        while ($sub_row = $sub_stmt->fetch(PDO::FETCH_ASSOC)) {
            $res[$row['order_number']]['skis_in_order'][] = $sub_row;
        }
    }

    return $res;
}

/**
 * In this function, we WOULD HAVE USED transactions, if it were possible. We need NESTED
 * transactions to make this work, something of which PDO does NOT support.
 * @param PDO $dbInstance
 * @param array $params
 * @return array
 */
function createNewOrder(PDO $dbInstance, mixed $params): array
{
    $total_price = 0;
    foreach ($params['skis_in_order'] as $ski) {
        $ski_instance = getSkiByID($dbInstance,$ski['ski_id']);
        $total_price += $ski_instance['msrp'] * $ski['quantity'];
    }

    $query = "INSERT INTO orders (total_price, customer_rep, order_state, customer_id)
              VALUES (:price,:cus_rep,1,:cid)";
    $stmt = $dbInstance->prepare($query);
    $stmt->bindValue(":price", $total_price);
    $stmt->bindValue(":cus_rep", $params['customer_rep']);
    $stmt->bindValue(":cid", $params['customer_id']);
    $stmt->execute();
    $stmt->fetchAll();

    $last_order_query = "SELECT `order_number` FROM orders WHERE `order_number` = LAST_INSERT_ID()";
    $last_order_stmt  = $dbInstance->prepare($last_order_query);
    $last_order_stmt->execute();
    $inserted_order = $last_order_stmt->fetch(PDO::FETCH_ASSOC);

    $ski_query = "INSERT INTO `skis_in_order` (order_number,ski_id,quantity)
                  VALUES (:order_num,:ski_id,:quantity)";
    foreach ($params['skis_in_order'] as $ski) {
        $ski_stmt = $dbInstance->prepare($ski_query);
        $ski_stmt->bindValue(":order_num",$inserted_order['order_number']);
        $ski_stmt->bindValue(":ski_id",$ski['ski_id']);
        $ski_stmt->bindValue(":quantity",$ski['quantity']);
        $ski_stmt->execute();
        $ski_stmt->fetchAll();
    }
    $data['body'] = "";
    $data['status'] = 204;
    return $data;

}

/**
 * Deletes a single order from the orders table.
 * @param PDO $dbInstance
 * @param int $customer_id
 * @param int $order_number
 */
function deleteOrder(PDO $dbInstance, int $customer_id, int $order_number) {
    $query = "SELECT COUNT(*) FROM orders WHERE order_number = :order_number AND id = :customer_id";
    $stmt = $dbInstance->prepare($query);
    $stmt->bindValue(":customer_id", $customer_id);
    $stmt->bindValue(":order_number", $order_number);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_NUM);
    if ($row[0] != 1) {
        throw new RuntimeException("Order does not exist or is not associated with this customer id");
    }

    // Delete skis in order for this order
    $query = "DELETE FROM skis_in_order WHERE order_number = :order_number";
    $stmt = $dbInstance->prepare($query);
    $stmt->bindValue(":order_number", $order_number);
    $stmt->execute();

    // Delete actual order
    $query = "DELETE FROM orders WHERE order_number = :order_number";
    $stmt = $dbInstance->prepare($query);
    $stmt->bindValue(":order_number", $order_number);
    $stmt->execute();
}

/**
 * @param PDO $dbInstance
 * @param string $ski_id
 * @return array
 */
function getSkiByID(PDO $dbInstance, string $ski_id): array {
    $res = array();
    $query = "SELECT `id`,`temp_class`,`grip`,`description`,`msrp`, `type`,
                     `model`, `weight`, `size` FROM ski where id = :ski_id";
    $stmt  = $dbInstance->prepare($query);
    $stmt->bindValue(":ski_id",$ski_id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * @param PDO $dbInstance
 * @return array
 */
function getProductionPlan(PDO $dbInstance): array
{
    $res = array();
    $query = "SELECT num_of_skies, ski_type, manager FROM production_plan";
    $stmt = $dbInstance->prepare($query);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $res[] = $row;
    }

    return $res;
}
