<?php

function getShipments(PDO $dbInstance): array
{
    $res = array();
    $query = "SELECT * FROM ready_shipments";
    $stmt = $dbInstance->prepare($query);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $res[] = $row;
    }

    return $res;
}
