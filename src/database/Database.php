<?php


class Database
{
    /**
     * @var PDO - the database connection instance
     */
    private PDO $db;

    /**
     * Database constructor.
     */
    public function __construct()
    {
        $this->db = new  PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8',
        DB_USER,DB_PWD,
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }

    /**
     * @return PDO - the active database instance.
     */
    public function getDB(): PDO {
        return $this->db;
    }
}