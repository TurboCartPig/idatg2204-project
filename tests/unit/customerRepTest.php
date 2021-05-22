<?php

use DBProject\Database\Database;

require_once './src/endpoints/customerRep.php';

class CustomerRepTest extends \Codeception\Test\Unit
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
    public function test()
    {
        $res = fetchOrders($this->db->getDB(), 1);

        $this->assertCount(8, $res);
    }

    public function testShipmentCreation()
    {
        $body['address_id'] = '1';
        $body['pickup_date'] = '2021-03-01';
        $body['order_number'] = '2';
        $body['transporter_id'] = '2';
        $body['driver_id'] = '2';

        $res = createShipment($this->db->getDB(), 1, $body);

        $this->tester->seeInDatabase('shipment',array('address_id' => '1', 'pickup_date' => '2021-03-01',
                                                      'order_number' => '3','transporter_id' => '2', 'driver_id' => '2'));
//        $this->tester->seeInDatabase('shipment', array('address_id' => '1', 'driver_id' => '2'));
    }
}