<?php
require_once 'controller/APIController.php';

class controllerTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests

    public function testIsInvalidCarResourceEndpoint()
    {
        $controller = new APIController();
        self::assertEquals(false, $controller->isValidEndpoint(['cars', 'JD12345'], 'GET', [], []));
    }

    public function testExistingCarResource()
    {
        $controller = new APIController();
        $res = $controller->handleRequest(['cars', '10'], 'GET', [], []);
        self::assertNotEmpty($res);
        if (isset($res['id'])) {
            self::assertEquals(10, $res['id']);
        }
    }

    public function testNonExistingCarResource()
    {
        $controller = new APIController();
        $res = $controller->handleRequest(['cars', '30'], 'GET', [], []);
        self::assertEmpty($res);
    }
}