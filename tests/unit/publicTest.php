<?php

use DBProject\Database\Database;

require_once './src/endpoints/public.php';

class publicTest extends \Codeception\Test\Unit
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
    public function testGetSkis()
    {

        $res = getSkis($this->db->getDB());

        $this->assertCount(4, $res);
    }
}