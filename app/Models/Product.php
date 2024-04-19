<?php 

namespace App\Models; 

use PDO; 

class Product 
{
    public function __construct(
        protected PDO $conn 
    ){}

    public function count(?string $category = null) :int 
    {
        /*
        * Count items in database
        */

        $total = 0; 

        $sql = "SELECT COUNT(*) AS total FROM product ";

        if ($category){
            $sql .= "WHERE category=:category";

            $stm = $this->conn->prepare($sql);
            if ($stm) {
                //$res = $stm->execute(PDO::FETCH_OBJ);
                $res = $stm->execute([
                    'category' => $category 
                ]);
                if ($res){
                    $result = $stm->fetch(PDO::FETCH_ASSOC);
                    if ($result){
                        $total = $result["total"];
                    }
                }

                return $total;
            };
        }

        $stm = $this->conn->query($sql);

        if ($stm && $stm->rowCount()) {
            $result = $stm->fetch(PDO::FETCH_OBJ);
            $total = $result->total;
        };

        return $total; 
    }

    public function get(int $id) :array 
    {
        /*
        * Get item in database by id
        */

        $ret = []; 

        $sql = "SELECT * FROM product WHERE id=:id";

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

    public function getAll(array $params) :array 
    {
        /*
        * Get all items in database
        */

        $result = []; 
        $limit = $params["limit"];
        $start = $params["start"];
        $search = $params["search"];
        $searchBy = $params["search-by"];
        $orderBy = $params["order-by"];
        $orderDir = $params["order-dir"];

        $sql = "select p.* from product as p ";
        if ($search){
            switch ($searchBy) {
                case 'brand':
                    $sql .= "WHERE p.brand LIKE '%$search%' ";
                    break;
                case 'status':
                    $stat = getStatus($search);
                    $sql .= "WHERE p.status LIKE '%$stat%' "; 
                    break;
                case 'code':
                    $sql .= "WHERE p.code LIKE '%$search%' "; 
                    break;
                case 'price up to':
                    $price = (int) $search;
                    $sql .= "WHERE p.price <= $price ";
                    break;
                default:
                    $sql .= "WHERE p.name LIKE '%$search%' ";
                    break;
            }
        }

        if ($orderBy && $orderDir){
            $sql .= "ORDER BY p.$orderBy $orderDir ";
        }

        $sql .= "LIMIT $start, $limit ";

        $stm = $this->conn->query($sql);
        if ($stm && $stm->rowCount()){
            $result = $stm->fetchAll();
        }

        return $result;
    }

    public function getByCategory(string $category, ?array $params = null) :array 
    {
        /*
        * Get items by category name
        */

        $result = []; 

        $sql = "SELECT * FROM product WHERE category=:category ";

        if ($params){
            $limit = $params["limit"];
            $start = $params["start"];
            $orderBy = $params["order-by"];
            $orderDir = $params["order-dir"];

            $sql .= "ORDER BY $orderBy $orderDir ";
            $sql .= "LIMIT $start, $limit";
            
        }

        $stm = $this->conn->prepare($sql); 
        if ($stm){
            $res = $stm->execute([
                'category' => $category, 
            ]);
            if ($res){
                $ret = $stm->fetchAll(PDO::FETCH_ASSOC);
            };
        }
        return $ret; 
    }

    public function save(array $data) :bool 
    {
        /*
        * Save item in database
        */

        $ret = []; 

        $sql = "INSERT INTO product (name, brand, status, number, code, price, category, added) values ";
        $sql .= "(:name, :brand, :status, :number, :code, :price, :category, NOW())";

        $stm = $this->conn->prepare($sql); 

        if ($stm){
            $res = $stm->execute([
                'name' => $data["name"],
                'brand' => $data["brand"], 
                'status' => $data["status"], 
                'number' => $data["number"], 
                'code' => $data["code"], 
                'price' => $data["price"], 
                'category' => $data["category"] 
            ]);

            if (!$res){
                $_SESSION['message'] = "Something went wrong";
            }

            return $stm->rowCount();
        };

        return $ret; 
    }

    public function update(array $data, int $id) :bool 
    {
        /*
        * Update item in database
        */

        $ret = False; 

        $sql = "UPDATE product SET name=:name, brand=:brand, status=:status, number=:number, code=:code, price=:price, category=:category ";
        $sql .= "WHERE id=:id";
        
        $stm = $this->conn->prepare($sql); 

        if ($stm){
            $res = $stm->execute([
                "name" => $data["name"],
                "brand" => $data["brand"],
                "status" => $data["status"],
                "number" => $data["number"],
                "code" => $data["code"],
                "price" => $data["price"],
                "category" => $data["category"],
                "id" => $id,
            ]);

            return $stm->rowCount(); 
        }; 

        return $ret; 
    }

    public function delete(int $id) :int 
    {
        /*
        * Delete item from database
        */
        
        $ret = 0; 

        $sql = "DELETE FROM product ";
        $sql .= "WHERE id=:id";
        
        $stm = $this->conn->prepare($sql); 

        if ($stm){
            $stm -> bindParam('id', $id, PDO::PARAM_INT);
            $res = $stm->execute(); 
            return $stm->rowCount();
        }; 

        return $ret; 
    }

    public function decreaseNumber(int $id, int $number) :bool 
    {
        /**
        * Decrease number of a product
        */

        $ret = False; 

        $sql = "UPDATE product SET number=:number ";
        $sql .= "WHERE id=:id";

        $stm = $this->conn->prepare($sql); 
        
        if ($stm) {
            $res = $stm->execute([
                "number" => $number, 
                "id" => $id
            ]);
            return $stm->rowCount(); 
        }
        
        return $ret; 
    }

    public function decreaseProducts(array $products) :bool
    {
        /*
        * Taken a sold products list, decrease, for each of them, the number in the database
        */

        $ret = True;

        foreach($products as $product){
            $item = $this->get($product["id"]);
            if ($item["number"] > 1){
                $number = $item["number"] - $product["amount"];
                $res = $this->decreaseNumber($product["id"], $number);
                if (!$res){
                   $ret = False; 
                }
            } else if($item["number"] == 1){
                $res = $this->delete($product["id"]);
                if (!$res){
                    $ret = False; 
                 }
            }
        }

        return $ret;
    }

    public function getByString(array $data) :array 
    {
        /*
        * query for search form suggestions
        * get on the base of "search by" and string entered in 
        * input field
        */

        $ret = [];

        $by = $data["by"];
        $string = "%".$data["string"]."%";

        $sql = "SELECT * FROM product WHERE $by LIKE :string";

        $stm = $this->conn->prepare($sql);
        $stm->bindParam(':string', $string, PDO::PARAM_STR);
        $stm->execute();

        if ($stm->rowCount()) {
            $results = $stm->fetchAll(PDO::FETCH_OBJ);

            switch ($by) {
                case 'brand': 
                    foreach($results as $result){
                        if (!in_array($result->brand, $ret)){
                            $ret[] = $result->brand;
                        };
                    }; 
                    break;
                default:
                    foreach($results as $result){
                        if (!in_array($result->name, $ret)){
                            $ret[] = $result->name;
                        };
                    }; 
                    break;
            };

            return $ret;
        } 

        return $ret; 
    }
}