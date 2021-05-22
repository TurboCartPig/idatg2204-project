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
    $query = "INSERT INTO orders (total_price, customer_rep, order_state, id)
              VALUES (:price,:cus_rep,1,:cid)";
    $stmt = $dbInstance->prepare($query);
    $stmt->bindValue(":price", $params['price']);
    $stmt->bindValue(":cus_rep", $params['customer_rep']);
    $stmt->bindValue(":cid", $params['customer_id']);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $res[] = $row;
    }

    return $res;
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

    $query = "DELETE FROM orders WHERE order_number = :order_number AND id = :customer_id";
    $stmt = $dbInstance->prepare($query);
    $stmt->bindValue(":customer_id", $customer_id);
    $stmt->bindValue(":order_number", $order_number);
    $stmt->execute();
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
