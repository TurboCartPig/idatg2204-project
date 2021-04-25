<?php
require_once './src/endpoints/customer.php';
require_once './src/database/Database.php';

class customerRepTest extends \Codeception\Test\Unit
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
    public function testOrderCreation()
    {
        $db = new Database();

        $body['price'] = '256000';
        $body['customer_rep'] = '2';
        $body['customer_id'] = '3';

        $res = createNewOrder($db->getDB(), $body);

        $this->tester->seeInDatabase('orders', array('total_price' => '256000', 'customer_rep' => '2', 'customer_id' => '3'));

    }
}