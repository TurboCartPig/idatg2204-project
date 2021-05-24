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

    public function testGetOrdersForEmployee() {

        $employeeID = 2;
        $res = fetchOrders($this->db->getDB(),$employeeID);

        $this->assertCount(2,$res);

        $first_order = $res[0];

        $this->assertEquals('147000',$first_order['total_price']);
        $this->assertEquals('2',$first_order['customer_id']);

        $second_order

        $employeeID = 3;
        $res = fetchOrders($this->db->getDB(),$employeeID);

        $this->assertCount(3,$res);
    }

    public function testShipmentCreation()
    {
        $body['address_id'] = '1';
        $body['pickup_date'] = '2021-03-01';
        $body['order_number'] = '2';
        $body['transporter_id'] = '2';
        $body['driver_id'] = '2';

        $employeeID = 3;

        $this->tester->dontSeeInDatabase('shipment',array('address_id' => '1', 'pickup_date' => '2021-03-01',
            'order_number' => '2','transporter_id' => '2', 'driver_id' => '2'));

        $res = createShipment($this->db->getDB(), $employeeID, $body);

        $this->tester->seeInDatabase('shipment',array('address_id' => '1', 'pickup_date' => '2021-03-01',
                                                      'order_number' => '2','transporter_id' => '2', 'driver_id' => '2'));
//        $this->tester->seeInDatabase('shipment', array('address_id' => '1', 'driver_id' => '2'));
    }
}