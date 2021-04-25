<?php

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