<?php 

namespace App\Models; 

use PDO; 

class Invoice
{
    public function __construct(
        protected ?PDO $conn 
    ){}

    public function count() :int 
    {
        /*
        * Count invoice items in database
        */

        $total = 0; 

        $sql = "SELECT COUNT(*) AS total FROM invoice";

        $stm = $this->conn->query($sql);

        if ($stm && $stm->rowCount()) {
            $result = $stm->fetch(PDO::FETCH_OBJ);
            $total = $result->total;
        };

        return $total;
    }

    public function getAll(array $params) :array 
    {
        /*
        * Get all invoice items in database
        */

        $result = []; 
        $limit = $params["limit"];
        $start = $params["start"];
        $search = $params["search"];
        $orderBy = $params["order-by"];
        $orderDir = $params["order-dir"];

        $sql = "SELECT * FROM invoice ";

        if ($search){
            $sql .= "WHERE customer LIKE '%$search%' ";
        }

        $sql .= "ORDER BY $orderBy $orderDir ";
        $sql .= "LIMIT $start, $limit";

        $stm = $this->conn->query($sql);
        if ($stm && $stm->rowCount()){
            $result = $stm->fetchAll();
        }

        return $result;
    }

    public function save(string $name, string $file) :int  
    {
        /**
        * Create a new Invoice 
        *    
        * Invoice object has following fileds: 
        * id (A.I.), customer, file 
        *
        */
        
        $ret = False;
        
        $sql = "INSERT INTO invoice (date, customer, file) VALUES ";
        $sql .= "(NOW(), :customer, :file)";

        $stm = $this->conn->prepare($sql); 

        if ($stm){
            $res = $stm->execute([
                'customer' => $name, 
                'file' => $file, 
            ]);

            if ($res){
                $last =  $this->conn->lastInsertId();
                return $last;
            } else {
                return null;
            }
        } else {
            return null;
        }

        return $ret; 
    }
}