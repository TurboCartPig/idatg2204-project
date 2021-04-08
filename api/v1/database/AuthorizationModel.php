<?php
require_once 'Database.php';

/**
 * Class AuthorisationModel
 */
class AuthorizationModel extends Database
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * A simple authorisation mechanism - just checking that the token matches the one in the database
     * @param string $token
     * @return bool indicating whether the token was successfully verified
     */
    public function isValid(string $token): bool
    {
        $query = 'SELECT COUNT(*) FROM auth_token WHERE token = :token';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':token', $token);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_NUM);
        if ($row[0] == 0) {
            return false;
        } else {
            return true;
        }
    }

}