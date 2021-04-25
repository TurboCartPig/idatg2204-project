<?php
require './src/endpoints/customerRep.php';
require './src/database/Database.php';

class customerRepTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var \PDODemo
     */
    protected $pdoDemo;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function test()
    {
        $db = new Database();
        $res = fetchOrders($db->getDB(),1);

        $this->assertCount(8,$res);
    }

//    public function testInsertExistingModelNewBrand()
//    {
//        $pdoDemo = new PDODemo();
//        $pdoDemo->runInsert('Ford', 'Explorer');
//
//        $this->tester->seeInDatabase('car_brand', array('make' => 'Ford'));
//        $this->tester->seeInDatabase('car_model', array('make' => 'Ford', 'model' => 'Explorer'));
//    }
//
//    public function testInsertNewModelNewBrand()
//    {
//        $pdoDemo = new PDODemo();
//        $pdoDemo->runInsert('Audi', 'A3');
//
//        $this->tester->seeInDatabase('car_brand', array('make' => 'Audi'));
//        $this->tester->seeInDatabase('car_model', array('make' => 'Audi', 'model' => 'A3'));
//    }
//
//    public function testComplexUpdate()
//    {
//        $pdoDemo = new PDODemo();
//        $pdoDemo->runComplexUpdate(array('Nordland', 'Troms og Finnmark'));
//        $this->tester->seeInDatabase('car_model', array('make' => 'Audi', 'model' => 'A3'));
//        $this->tester->seeInDatabase('car_model', array('make' => 'Volkswagen', 'model' => 'Passat'));
//        $this->tester->dontSeeInDatabase('car_model', array('make' => 'Audi', 'model' => 'Q3'));
//        $this->tester->dontSeeInDatabase('car_model', array('make' => 'Mazda', 'model' => 'CX-3'));
//    }
//
//    public function testComplexQuery()
//    {
//        $pdoDemo = new PDODemo();
//        $res = $pdoDemo->runComplexQuery('Volkswagen', 'Audi');
//
//    }
}