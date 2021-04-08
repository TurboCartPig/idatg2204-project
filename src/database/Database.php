<?php


class Database
{
    /**
     * @var PDO
     */
    private $db;

    public function __construct()
    {
        $this->db = new  PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8',
        DB_USER,DB_PWD,
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }

    public function simpleQuery(): array {
        $retu = array();
        $stmt = "SELECT * FROM customer";
        $res = $this->db->query($stmt);
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            $retu[] = $row['id'];
        }
        return $retu;
    }
}