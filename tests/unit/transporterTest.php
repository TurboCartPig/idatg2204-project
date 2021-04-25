<?php
require_once './src/endpoints/transporters.php';
require_once './src/database/Database.php';

class transporterTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected UnitTester $tester;


    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testGetShipments()
    {
        $db = new Database();

        $res = getShipments($db->getDB());

        $this->assertCount(1,$res);
    }
}