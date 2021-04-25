<?php

/**
 * @param PDO $dbInstance
 * @return array
 */
function getSkis(PDO $dbInstance): array
{
    $res = array();
    $query = "SELECT * from ski";
    $stmt = $dbInstance->prepare($query);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $res[] = $row;
    }

    return $res;
}
