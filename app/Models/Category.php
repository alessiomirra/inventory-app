<?php 

namespace App\Models; 

use PDO; 

$configs = require "./config/app.config.php";


class Category 
{
    public function __construct(
        protected PDO $conn 
    )
    {}

    public function get(int $id) :array 
    {
        /*
        * Get item in database by id
        */

        $ret = []; 

        $sql = "SELECT * FROM category WHERE id=:id";

        $stm = $this->conn->prepare($sql);
        if ($stm){
            $res = $stm->execute([
                'id' => $id, 
            ]);
            if ($res){
                $ret = $stm->fetch(PDO::FETCH_ASSOC);
            };
        }

        return $ret; 
    }

    public function getAll() :array 
    {
        /*
        * Get all items in database
        */

        $result = []; 

        $sql = "SELECT * FROM category ORDER BY id DESC";

        $stm = $this->conn->query($sql);
        if ($stm && $stm->rowCount()){
            $result = $stm->fetchAll();
        }

        return $result;
    }

    public function checkExist(string $name) :bool 
    {
        /**
        * Check if a category exist 
        */

        $exists = False;

        $sql = "SELECT * FROM category WHERE name = '$name'";

        $result = $this->conn->query($sql);
        if ($result && $result->rowCount() > 0){
            $exists = True;
        }
        return $exists;
    }

    public function save(array $data) :bool 
    {
        /**
        * Create a new category 
        */

        $ret = [];

        $sql = "INSERT INTO category (name) values ";
        $sql .= "(:name)";

        $stm = $this->conn->prepare($sql); 

        if ($stm){
            $res = $stm->execute([
                "name" => $data["name"]
            ]);

            if (!$res){
                $_SESSION['message'] = "Something went wrong";
            }

            return $stm->rowCount();
        };

        return $ret; 
    } 

    public function delete(int $id) :int 
    {
        /**
        * Delete a category 
        */

        $ret = 0;

        $sql = "DELETE FROM category ";
        $sql .= "WHERE id=:id";

        $stm = $this->conn->prepare($sql);

        if ($stm){
            $stm -> bindParam('id', $id, PDO::PARAM_INT);
            $res = $stm->execute(); 
            return $stm->rowCount();
        }; 

        return $ret;
    }
}