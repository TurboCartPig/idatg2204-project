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

        $first_ski = $res[0];

        $this->assertEquals('skin',$first_ski['grip']);
        $this->assertEquals('4900',$first_ski['msrp']);
        $this->assertEquals('6',$first_ski['weight']);

        $third_ski = $res[2];

        $this->assertEquals('skin',$third_ski['grip']);
        $this->assertEquals('4300',$third_ski['msrp']);
        $this->assertEquals('5',$third_ski['weight']);
    }
}