<?php

class PublicEndpointCest
{
    public function _before(ApiTester $I)
    {
    }

    public function test(ApiTester $I)
    {
        $I->sendGet('/public/skis');
        $I->seeResponseCodeIs('200');
        $I->seeResponseIsJson();

        // Test that it contains an example ski
        $I->seeResponseContainsJson(array('id' => '1', 'temp_class' => 'cold', 'grip' => 'skin', 'msrp' => '4900'));
    }
}
