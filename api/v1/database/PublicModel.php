<?php
require_once 'Database.php';
require_once 'AbstractModel.php';
require_once 'controller/BadRequestException.php';

/**
 * Class DealerModel class for accessing dealer data in database.
 */
class PublicModel extends AbstractModel
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Returns the collection of resources from the database.
     * @param array|null $filter
     * @return array an array of associative arrays of resource attributes. The
     *               array will be empty if there are no resources to be returned.
     */
    function getCollection(array $filter = null): array
    {
        $res = array();

        $query = 'SELECT id, start_date, end_date, customer_rep FROM customer';
        if ($filter['customers']) {
            $query .= ' WHERE name IN (:p0';
            for ($i = 1; $i < count($filter['customers']); $i++) {
                $query .= ',:p' . $i;
            }
            $query .= ')';
            $stmt = $this->db->prepare($query);
            for ($i = 0; $i < count($filter['customers']); $i++) {
                $stmt->bindValue(':p' . $i, $filter['customers'][$i]);
            }
            $stmt->execute();
        } else {
            $stmt = $this->db->query($query);
        }
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $res[] = array('id' => intval($row['id']), 'start_date' => $row['start_date'], 'end_date' => $row['end_date'], 'customer_rep' => $row['customer_rep']);
        }
        return $res;
    }

    /**
     * Returns the collection of resources from the database.
     * @param array|null $filter
     * @return array an array of associative arrays of resource attributes. The
     *               array will be empty if there are no resources to be returned.
     */
    function getCustomerSummary(array $filter = null): array
    {
        $res = array();

        $query = 'SELECT num_of_skies, ski_type, manager FROM production_plan';
        if ($filter['summary']) {
            $query .= ' WHERE name IN (:p0';
            for ($i = 1; $i < count($filter['summary']); $i++) {
                $query .= ',:p' . $i;
            }
            $query .= ')';
            $stmt = $this->db->prepare($query);
            for ($i = 0; $i < count($filter['summary']); $i++) {
                $stmt->bindValue(':p' . $i, $filter['summary'][$i]);
            }
            $stmt->execute();
        } else {
            $stmt = $this->db->query($query);
        }
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $res[] = array('num_of_skies' => intval($row['num_of_skies']), 'ski_type' => $row['ski_type'], 'manager' => $row['manager']);
        }
        return $res;
    }

    /**
     * Returns the collection of resources from the database.
     * @param int $id the id of the resource to be retrieved.
     * @return array an associative array of resource attributes - or null if
     *               no resources have the given id.
     * @throws BadRequestException in the case the request from the client is badly formatted or violates application
     *         or database constraints.
     */
    function getOneCustomer(int $id): ?array
    {
        $res = null;
        $query = 'SELECT id, start_date, end_date, customer_rep FROM customer WHERE customer.id = :id';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $res = array();
            $res['id'] = intval($row['id']);
            $res['start_date'] = $row['start_date'];
            $res['end_date'] = $row['end_date'];
            $res['customer_rep'] = $row['customer_rep'];
        }
        return $res;
    }

    /**
     * Creates a new resource in the database.
     * @param array $resource the resource to be created.
     * @return array an associative array of resource attributes representing
     *               the resource - the returned value will include the id
     *               assigned to the resource.
     * @throws BadRequestException if the record cannot be inserted into the database, because the county is not present in
     *               the database
     */
    function createCustomer(array $resource): array
    {
        $this->db->beginTransaction();
        $rec = $this->verifyResource($resource, true);
        if ($rec['code'] != RESTConstants::HTTP_OK) {
            $this->db->rollBack();
            if (isset($rec['detailCode'])) {
                throw new BadRequestException($rec['code'], $rec['detailCode']);
            } else {
                throw new BadRequestException($rec['code']);
            }
        }

        $res = array();
        $query = 'INSERT INTO customer (start_date, end_date, customer_rep) SELECT :start_date, end_date, customer_rep FROM customer';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':start_date', $resource['start_date']);
        $stmt->bindValue(':end_date', $resource['end_date']);
        $stmt->bindValue(':customer_rep', $resource['customer_rep']);
        $stmt->execute();

        $res['id'] = intval($this->db->lastInsertId());
        $res['start_date'] = $resource['start_date'];
        $res['end_date'] = $resource['end_date'];
        $res['customer_rep'] = $resource['customer_rep'];
        $this->db->commit();

        return $res;
    }

    /**
     * Modifies a resource in the database.
     * @param array $resource the resource to be modified.
     *                      attributes.
     * @return array
     */
    function updateResource(array $resource): array
    {
        return [];
    }


    /**
     * Deletes a resource from the database.
     * @param int $id the id of the resource to be deleted.
     *                      e.g., due to foreign key constraints.
     */
    function deleteResource(int $id)
    {

    }

    /**
     * Checks the format of the resource array to see that it satisfy the database schema. The test will only verify the
     * presence and type of database columns, additional keys passed will be ignored.
     * @param array $resource the resource represented as an associative array of the format
     *                        array('id' => integer, 'city' => string, 'county' => 'string')
     * @param bool $ignoreId a flag specifying whether the presence of the id attribute should be checked (should be true
     *                       when checking before a call to createResource()
     * @return array an array of the form array('code' => integer, 'detailCode' => integer) where the code is referring to
     *                       one of the types of DB error. detail will hold the code of the dealer-county FK error if
     *                       the name of the county does not match a known county in the database.
     * @see RESTConstants.
     */
    function verifyResource(array $resource, bool $ignoreId = false): array
    {
        $res = array();

        if (!$ignoreId && !array_key_exists('id', $resource)) {
            $res['code'] = RESTConstants::DB_ERR_ATTRIBUTE_MISSING;
            return $res;
        }
        if (!array_key_exists('city', $resource) && !array_key_exists('county', $resource)) {
            $res['code'] = RESTConstants::DB_ERR_ATTRIBUTE_MISSING;
            return $res;
        }

        if (!$this->isCountyExisting($resource['county'])) {
            $res['code'] = RESTConstants::DB_ERR_FK_INTEGRITY;
            $res['detailCode'] = RESTConstants::DB_FK_DEALER_COUNTY;
            return $res;
        }

        $res['code'] = RESTConstants::HTTP_OK;
        return $res;
    }

    /**
     * Checks if the given county exists in the database.
     * @param string $name the name of the county.
     * @return bool indicates the existence.
     */
    protected function isCountyExisting(string $name): bool
    {
        $query = 'SELECT COUNT(*) FROM county WHERE name = :name';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':name', $name);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_NUM);
        if ($row[0] == 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Checks if the given dealer has used cars for sale.
     * @param string $dealer_id the id of the dealer.
     * @return bool indicates the existence of children.
     */
    protected function hasCars(string $dealer_id): bool
    {
        $query = 'SELECT COUNT(*) FROM car WHERE dealer_id = :dealer_id';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':dealer_id', $dealer_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_NUM);
        if ($row[0] == 0) {
            return false;
        } else {
            return true;
        }
    }

}
