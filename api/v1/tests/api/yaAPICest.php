<?php

class yaAPICest
{
    public function _before(ApiTester $I)
    {
    }

    // tests
    public function getExistingCarTest(ApiTester $I)
    {
        $I->sendGet('/cars/10');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['id' => 10]);
    }

    public function getNonExistingCarTest(ApiTester $I)
    {
        $I->sendGet('/cars/30');
        $I->seeResponseCodeIs(404);
    }
}
