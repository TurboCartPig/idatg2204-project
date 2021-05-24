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
    public function testOrderCreationSingleSki()
    {
        $ski['ski_id'] = "3";
        $ski['quantity'] = "50";

        $body['customer_rep'] = '2';
        $body['customer_id'] = '3';
        $body['skis_in_order'][] = $ski;

        $this->tester->dontSeeInDatabase('orders', array('total_price' => '215000', 'customer_rep' => '2', 'customer_id' => '3'));

        $res = createNewOrder($this->db->getDB(), $body);

        $this->tester->seeInDatabase('orders', array('total_price' => '215000', 'customer_rep' => '2', 'customer_id' => '3'));
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

        $res = createNewOrder($this->db->getDB(), $body);

        $this->tester->seeInDatabase('orders', array('total_price' => '606800', 'customer_rep' => '2', 'customer_id' => '3'));
    }
}