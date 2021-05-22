<?php

use DBProject\Database\Database;

require_once './src/endpoints/transporters.php';

class transporterTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected UnitTester $tester;

    /**
     * @var Database Database object.
     */
    protected Database $db;

    protected function _before()
    {
        $this->db = new Database();
    }

    protected function _after()
    {
    }

    // tests
    public function testGetShipments()
    {
        $res = getShipments($this->db->getDB());

        $this->assertCount(1, $res);
    }
}