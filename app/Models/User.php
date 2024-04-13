<?php 

namespace App\Models; 

use PDO; 

class User
{

    public function __construct(
        protected PDO $conn 
    ){}

    public function getUserByEmail(string $email) :object
    {
        $result = new \stdClass();
        if (!$email) {
            return $result;
        }

        $sql = 'SELECT * FROM user WHERE email = :email';
        $stm = $this->conn->prepare($sql); 
        $res = $stm->execute(['email'=> $email]);

        if ($res && $stm->rowCount()){
            $result = $stm->fetch();
        }

        return $result; 
    }

    public function getUserByID(int $userID) :object 
    {
        $result = new \stdClass(); 

        $sql = 'SELECT * FROM user WHERE id = :id';
        $stm = $this->conn->prepare($sql);
        $res = $stm->execute(['id' => $userID]); 

        if ($res && $stm->rowCount()){
            $result = $stm->fetch();
        }

        return $result; 
    }

}