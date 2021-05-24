<?php

use DBProject\Database\Database;

require_once './src/endpoints/customer.php';

class CustomerTest extends \Codeception\Test\Unit
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
    public function testFetchOrdersMadeByCustomer() {
        $customerID = 3;

        $res = getOrdersForCustomer($this->db->getDB(), $customerID);

        $this->assertCount(2,$res);

        // Order with ID 5
        $first_order = $res['5'];

        $this->assertEquals('130600',$first_order['total_price']);
        $this->assertEquals('5',$first_order['customer_rep']);


        // Order with ID 6
        $second_order = $res['6'];

        $this->assertEquals('269500',$second_order['total_price']);
        $this->assertEquals('3',$second_order['customer_rep']);
    }


    public function testOrderCreationSingleSki()
    {
        $ski['ski_id'] = "3";
        $ski['quantity'] = "50";

        $body['customer_rep'] = '2';
        $body['customer_id'] = '3';
        $body['skis_in_order'][] = $ski;

        $this->tester->dontSeeInDatabase('orders', array('total_price' => '215000', 'customer_rep' => '2', 'customer_id' => '3'));
        $this->tester->dontSeeInDatabase('skis_in_order', array('ski_id' => '3', 'quantity' => '50'));

        createNewOrder($this->db->getDB(), $body);

        $this->tester->seeInDatabase('orders', array('total_price' => '215000', 'customer_rep' => '2', 'customer_id' => '3'));
        $this->tester->seeInDatabase('skis_in_order', array('ski_id' => '3', 'quantity' => '50'));

    }


    public function testOrderCreationMultipleSkis()
    {
        $ski['ski_id'] = "3";
        $ski['quantity'] = "50";

        $ski_two['ski_id'] = "2";
        $ski_two['quantity'] = "60";

        $body['customer_rep'] = '2';
        $body['customer_id'] = '3';
        $body['skis_in_order'][] = $ski;
        $body['skis_in_order'][] = $ski_two;

        $this->tester->dontSeeInDatabase('orders', array('total_price' => '606800', 'customer_rep' => '2', 'customer_id' => '3'));
        $this->tester->dontSeeInDatabase('skis_in_order', array('ski_id' => '3', 'quantity' => '50'));
        $this->tester->dontSeeInDatabase('skis_in_order', array('ski_id' => '2', 'quantity' => '60'));

        createNewOrder($this->db->getDB(), $body);

        $this->tester->seeInDatabase('orders', array('total_price' => '606800', 'customer_rep' => '2', 'customer_id' => '3'));
        $this->tester->seeInDatabase('skis_in_order', array('ski_id' => '3', 'quantity' => '50'));
        $this->tester->seeInDatabase('skis_in_order', array('ski_id' => '2', 'quantity' => '60'));
    }

    public function testOrderDeletion() {
        $customer_id = 3;
        $order_number = 5;

        $this->tester->seeInDatabase('orders',array('order_number' => '5','customer_id' => '3'));

        deleteOrder($this->db->getDB(), $customer_id, $order_number);

        $this->tester->dontSeeInDatabase('orders',array('order_number' => '5','customer_id' => '3'));


        $customer_id = 2;
        $order_number = 3;

        $this->tester->seeInDatabase('orders',array('order_number' => '3','customer_id' => '2'));

        deleteOrder($this->db->getDB(), $customer_id, $order_number);

        $this->tester->dontSeeInDatabase('orders',array('order_number' => '3','customer_id' => '2'));
    }

    public function testGetProductionPlan() {

    }
}