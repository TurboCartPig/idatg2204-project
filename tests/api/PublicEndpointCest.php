<?php

class PublicEndpointCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests
    public function tryToTest(ApiTester $I)
    {
        $I->sendGet('/public/skis');
        $I->seeResponseCodeIs('200');
        $I->seeResponseIsJson();
    }
}
