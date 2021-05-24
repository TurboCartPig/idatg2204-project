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

        $shipment = $res[0];

        $this->assertEquals("1",$shipment['shipment_number']);
        $this->assertEquals("2",$shipment['order_number']);
        $this->assertEquals("Sverre Iversens Vei ",$shipment['street']);
        $this->assertEquals("31",$shipment['number']);
        $this->assertEquals("972",$shipment['postal_code']);
        $this->assertEquals("Oslo",$shipment['city']);
    }

    public function testUpdateShipmentState() {

        $shipment_number = 1;
        $shipment_state  = 1;

        $this->tester->seeInDatabase('shipment',array('shipment_number' => '1','shipment_state' => '2'));
        $this->tester->dontSeeInDatabase('shipment',array('shipment_number' => '1','shipment_state' => '1'));

        changeShipmentState($this->db->getDB(), $shipment_number, $shipment_state);

        $this->tester->seeInDatabase('shipment',array('shipment_number' => '1','shipment_state' => '1'));
        $this->tester->dontSeeInDatabase('shipment',array('shipment_number' => '1','shipment_state' => '2'));
    }

}