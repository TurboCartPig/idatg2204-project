<?php

/**
 * @param PDO $dbInstance
 * @param mixed $customerID
 * @return array
 */
function getOrdersForCustomer(PDO $dbInstance, mixed $customerID): array
{
    $res = array();
    $query = "SELECT * FROM orders 
                INNER JOIN customer ON customer.id = orders.customer_id
                WHERE :cid = orders.customer_id";
    $stmt = $dbInstance->prepare($query);
    $stmt->bindValue(":cid", $customerID);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $res[] = $row;
    }

    return $res;
}

/**
 * @param PDO $dbInstance
 * @param array $params
 * @return array
 */
function createNewOrder(PDO $dbInstance, array $params): array
{
    $res = array();
    $ski = getSkiByID($dbInstance,$params['skis_in_order']['ski_id']);
    $query = "INSERT INTO orders (total_price, customer_rep, order_state, customer_id)
              VALUES (:price,:cus_rep,1,:cid);              
              SELECT `order_number` FROM orders WHERE `order_number` = LAST_INSERT_ID();";
    $stmt = $dbInstance->prepare($query);
    $stmt->bindValue(":price", $params['skis_in_order']['quantity'] * $ski['msrp']);
    $stmt->bindValue(":cus_rep", $params['customer_rep']);
    $stmt->bindValue(":cid", $params['customer_id']);
    $stmt->execute();

    $inserted_order = $stmt->fetch(PDO::FETCH_ASSOC); // We know there will only be 1 row
    $ski_query = "INSERT INTO `skis_in_order`"
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

    // Delete skiis in order for this order
    $query = "DELETE FROM skiis_in_order WHERE order_number = :order_number"
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
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $res[] = $row;
    }
    return $res;
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
