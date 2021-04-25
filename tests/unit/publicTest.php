<?php
require_once './src/endpoints/public.php';
require_once './src/database/Database.php';

class publicTest extends \Codeception\Test\Unit
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
    public function testGetSkis()
    {
        $db = new Database();

        $res = getSkis($db->getDB());

        $this->assertCount(4,$res);
    }
}