<?php
namespace DBProject\Database;

use PDO;

require_once 'dbCredentials.php';

/**
 * Class Database
 */
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

    /**
     * @param string $token
     * @return bool - Whether or not the user is authorized or not
     */
    public function isAuthorized(string $token): bool {
        $query = 'SELECT COUNT(*) FROM auth_token where token = :token';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':token',$token);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_NUM);
        return $row[0] != 0;
    }
}