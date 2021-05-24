<?php

class CustomerEndpointCest
{
    public function _before(ApiTester $I)
    {
        $I->haveHttpHeader('token', 'SUPER_SECRET_HASHED_CODE');
    }

    // tests
    public function testGetOrders(ApiTester $I)
    {
        $I->sendGet('/customers/3/orders');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();

        $I->seeResponseContainsJson(array(
            '5' => [
                'order_number' => '5',
                'parent_number' => null,
                'customer_id' => '3',
                'customer_rep' => '5',
                'total_price' => '130600',
                'order_state' => '3',
                'skis_in_order' => [
                    'order_number' => '5',
                    'ski_id' => '2',
                    'quantity' => '20',
                    'order_state' => '3',
                ]
            ]
        ));
    }

    public function testPostOrder(ApiTester $I)
    {
        $I->sendPost('/customers/orders', array(
            'customer_id' => '3',
            'customer_rep' => '2',
            'skis_in_order' => [
                'ski_id' => '3',
                'quantity' => '50',
            ]
        ));
        $I->seeResponseCodeIsSuccessful();
    }

    public function testDeleteOrder(ApiTester $I)
    {
        // Delete the order
        $I->sendDelete('/customers/4/orders/8');
        $I->seeResponseCodeIsSuccessful();

        // Make sure it is deleted, note this is also tested by the unit tests
        $I->sendGet('/customers/4/orders');
        $I->dontSeeResponseContainsJson(array(
            '8' => [],
        ));
    }

    public function testGetProductionPlan(ApiTester $I)
    {
        $I->sendGet('/customers/summary');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();

        $I->seeResponseContainsJson(array(
            0 => [
                'num_of_skies' => '200',
                'ski_type' => '2',
                'manager' => '1',
            ]
        ));
    }
}
