<?php

class TransporterEndpointCest
{
    public function _before(ApiTester $I)
    {
        $I->haveHttpHeader('token', 'SUPER_SECRET_HASHED_CODE');
    }

    // tests
    public function testGetShipments(ApiTester $I)
    {
        $I->sendGet('/transporters/shipments');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();

        // Test that it contains an example shipment
        $I->seeResponseContainsJson(array(
            0 => [
                'shipment_number' => '1',
                'order_number' => '2',
                'street' => 'Sverre Iversens Vei ',
                'number' => '31',
                'postal_code' => '972',
                'city' => 'Oslo'
            ]
        ));
    }

    public function testUpdateShipmentState(ApiTester $I)
    {
        // Update the shipment
        $I->sendPut('/transporters/shipments/1');
        $I->seeResponseCodeIsSuccessful();

        // There should now be no shipments in the ready state
        $I->sendGet('/transporters/shipments');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseEquals('[]');
    }
}
