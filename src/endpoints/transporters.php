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

function changeShipmentState(PDO $dbInstance, int $shipment_number, int $state) {
    $query = "UPDATE shipment
                SET shipment_state = :state
                WHERE shipment_number = :shipment_number";
    $stmt = $dbInstance->prepare($query);
    $stmt->bindValue(':state', $state);
    $stmt->bindValue(':shipment_number', $shipment_number);
    $stmt->execute();
}