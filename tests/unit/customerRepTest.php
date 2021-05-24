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

        // Check that the count is what we expect
        $this->assertCount(2,$res);

        // Verifying that the actual orders contains the expected data
        // The order ID is 3
        $first_order = $res['3'];

        $this->assertEquals('147000',$first_order['total_price']);
        $this->assertEquals('2',$first_order['customer_id']);

        // The order ID is 8
        $second_order = $res['8'];

        $this->assertEquals('326500',$second_order['total_price']);
        $this->assertEquals('4',$second_order['customer_id']);

        $employeeID = 3;
        $res = fetchOrders($this->db->getDB(),$employeeID);

        // Check that the count is what we expect
        $this->assertCount(3,$res);

        // Verifying that the actual orders contains the expected data
        // The order ID is 2
        $first_order = $res['2'];

        $this->assertEquals('267730',$first_order['total_price']);
        $this->assertEquals('1',$first_order['customer_id']);

        // The order ID is 4
        $second_order = $res['4'];

        $this->assertEquals('136500',$second_order['total_price']);
        $this->assertEquals('2',$second_order['customer_id']);


        // The order ID is 6
        $third_order = $res['6'];

        $this->assertEquals('269500',$third_order['total_price']);
        $this->assertEquals('3',$third_order['customer_id']);
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