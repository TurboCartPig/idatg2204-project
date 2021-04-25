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
    $query = "INSERT INTO orders (total_price, customer_rep, order_state, customer_id)
              VALUES (:price,:cus_rep,'In production',:cid)";
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
 * @param PDO $dbInstance
 * @return array
 */
function getProductionPlan(PDO $dbInstance): array
{
    $res = array();
    $query = "SELECT * FROM production_plan";
    $stmt = $dbInstance->prepare($query);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $res[] = $row;
    }

    return $res;
}
