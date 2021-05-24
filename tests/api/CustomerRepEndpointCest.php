<?php

class CustomerRepEndpointCest
{
    public function _before(ApiTester $I)
    {
        $I->haveHttpHeader('token', 'SUPER_SECRET_HASHED_CODE');
        $I->haveHttpHeader('Content-Type', 'application/json');
    }

    // tests
    public function testGetOrders(ApiTester $I)
    {
        $I->sendGet('/customer_rep/5/orders');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(array(
            '5' => [
                'order_number' => '5',
                'customer_id' => '3',
                'customer_rep' => '5',
                'total_price' => '130600',
                'order_state' => '3',
                'skis_in_order' => [
                    0 => [
                        'order_number' => '5',
                        'ski_id' => '2',
                        'quantity' => '20',
                        'order_state' => '3',
                    ]
                ]
            ]
        ));
    }

    public function testChangeOrderStateNewToOpen(ApiTester $I)
    {
        // Update the state of order number 3
        $I->sendPut('/customer_rep/2/orders/3/open');
        $I->seeResponseCodeIsSuccessful();

        // Check that order number 3 has been updated
        $I->sendGet('/customer_rep/2/orders');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(array(
            '3' => [
                'order_number' => '3',
                'order_state' => '2',
            ]
        ));
    }

    public function testChangeOrderStateOpenToFilled(ApiTester $I)
    {
        // Update the state of order number 8
        $I->sendPut('/storekeeper/2/orders/8/filled');
        $I->seeResponseCodeIsSuccessful();

        // Check that order number 8 has been updated
        $I->sendGet('/customer_rep/2/orders');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(array(
            '8' => [
                'order_number' => '8',
                'order_state' => '3',
            ]
        ));
    }

    public function testCreateShipmentFromOrder(ApiTester $I)
    {
        $I->sendPost('/customer_rep/3/shipments', array(
            'order_number' => 2,
            'address_id' => 1,
            'pickup_date' => '2021-10-10',
            'transporter_id' => 1,
            'driver_id' => 1,
        ));
        $I->seeResponseCodeIsSuccessful();
    }
}
