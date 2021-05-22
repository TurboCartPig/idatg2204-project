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
    public function testOrderCreation()
    {
        $body['price'] = '256000';
        $body['customer_rep'] = '2';
        $body['customer_id'] = '3';

        $res = createNewOrder($this->db->getDB(), $body);

        $this->tester->seeInDatabase('orders', array('total_price' => '256000', 'customer_rep' => '2', 'customer_id' => '3'));
    }
}