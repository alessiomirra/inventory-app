<?php 

namespace App\Controllers; 

use App\Models\Product; 
use App\Models\Category;

use PDO; 
use Exception;

class ProductController extends BaseController
{
    protected Product $product;

    public function __construct(
        protected ?PDO $conn
    )
    {
        parent::__construct($conn);
        $this->product = new Product($conn);
    }
    
    public function home() :void 
    {
        /*
        * Homepage view
        */

        $this->redirectIfNotLoggedIn();

        // params
        $params = [];
        $params["limit"] = getConfig("recordsPerPage");

        // page 
        $page = getParam("page") ? (int) getParam("page") : 1; 
        $start = $params["limit"] * ($page - 1);
        if ($start < 0){
            $start = 0;
        }
        $params["start"] = $start;

        // search
        $search = !empty($_REQUEST["search"]) ? $_REQUEST["search"] : "";
        $searchBy = !empty($_REQUEST["search-by"]) ? $_REQUEST["search-by"] : "name"; 
        $params["search"] = $search; 
        $params["search-by"] = $searchBy; 

        // order by 
        $orderBy = getParam("order-by") ? getParam("order-by") : "id";
        $orderDir = getParam("order-dir") ? getParam("order-dir") : "DESC";
        $params["order-by"] = $orderBy;
        $params["order-dir"] = $orderDir;
        
        $count = $this->product->count();
        $products = $this->product->getAll($params);

        $this->content = view('home', compact("count", "products"), $this->tplDir);
    }

    public function cart() :void 
    {
        /*
        * Cart view
        */

        $this->redirectIfNotLoggedIn();

        $this->content = view("cart", [], $this->tplDir);
    }

    public function show(int $id) :void 
    {
        /**
        * Show Product Details Page 
        */

        $this->redirectIfNotLoggedIn();

        $product = $this->product->get($id); 
        $this->content = view('product', compact("product"), $this->tplDir); 
    }

    public function categoryPage(string $categoryName) :void 
    {
        /**
        * Page for a single category 
        */

        $this->redirectIfNotLoggedIn(); 

        $params = [];
        $params["limit"] = getConfig("recordsPerPage");

        // order by 
        $orderBy = getParam("order-by") ? getParam("order-by") : "id";
        $orderDir = getParam("order-dir") ? getParam("order-dir") : "DESC";
        $params["order-by"] = $orderBy;
        $params["order-dir"] = $orderDir;

        // pagination
        $page = getParam("page") ? (int) getParam("page") : 1; 
        $start = $params["limit"] * ($page - 1);
        if ($start < 0){
            $start = 0;
        }
        $params["start"] = $start;

        $category = $categoryName;

        $count = $this->product->count($category);
        $products = $this->product->getByCategory($category, $params); 

        $this->content = view("categoryProd", compact("count", "products", "category"), $this->tplDir);
    }

    public function getCategories() :void 
    {
        /*
        * Returns a list of all categories in database
        * Used by Vertical navbar
        * Consumed thrown an XMLHttpRequest request 
        */

        ob_end_clean();

        $categoryIns = new Category($this->conn); 
        $categories = $categoryIns->getAll();

        header('Content-Type: application/json');
        echo json_encode($categories);
        exit;
    }

    public function edit(int $id) :void 
    {
        /**
        * Show edit Page 
        */
        $this->redirectIfNotLoggedIn();

        $categoryIns = new Category($this->conn);
        $categories = $categoryIns->getAll();

        $product = $this->product->get($id); 
        $this->content = view('editProduct', compact("product", "categories"), $this->tplDir);
    }

    public function addProduct() :void 
    {
        /**
        * Show add product page 
        */
        $this->redirectIfNotLoggedIn();

        // Get categories
        $categoryIns = new Category($this->conn);
        $categories = $categoryIns->getAll();

        $this->content = view('addProduct', compact("categories"), $this->tplDir); 
    }

    public function saveProduct(?int $id=null) :void 
    {
        /*
        * Retrieve form data and call to save or update method
        */

        $this->redirectIfNotLoggedIn();

        $data = [
            "name" => strtoupper(trim($_POST["name"])) ?? "", 
            "brand" => strtoupper(trim($_POST["brand"])) ?? "",
            "status" => trim($_POST["status"]) ?? "", 
            "number" => $_POST["number"] ?? "", 
            "code" => $_POST["code"] ?? "",
            "category" => strtoupper(trim($_POST["category"])) ?? "", 
        ];

        if (isset($_POST["price"]) && !empty($_POST["price"])){
            $data["price"] = floatval($_POST["price"]);
        } else {
            $data["price"] = "";
        }

        if (!$id){
            $new = $this->product->save($data);
            // check if the new product category exists. If if doesn't exist, create a new one
            $catIns = new Category($this->conn);
            $cat = $catIns->checkExist($data["category"]);
            if (!$cat){
                $newCatData = [];
                $newCatData["name"] = $data["category"];
                $newCat = $catIns->save($newCatData);
            }

            if ($new !== null){
                $_SESSION['success'] = "Product Addedd Successfully!";
                redirect('/add');
            }
        } else {
            $product = $this->product->get($id); 
            if ($product){
                $res = $this->product->update($data, $id);
                if ($res){
                    $_SESSION["success"] = "Product edit successfully";
                    redirect("/$id/edit");
                } else {
                    $_SESSION["message"] = "Product Something went wrong";
                    redirect("/$id/edit");
                }
            } else {
                $_SESSION["message"] = "Product Something went wrong";
                redirect("/$id/edit");
            }
        }
    }

    public function saveFromFile() 
    {
        /**
        * Save products taken from CSV file in the database. API endpoint 
        */

        $filesFolder = __DIR__ . "/files";

        if (isset($_FILES["document"]) && $_FILES["document"]["error"] === UPLOAD_ERR_OK){
            $filepath = $filesFolder."/".$_FILES["document"]["name"];
            if (move_uploaded_file($_FILES["document"]["tmp_name"], $filepath)){
                $handle = fopen($filepath, "r");
                if ($handle !== False){
                    // Pass file line by line and take all objects
                    ////
                    $objects = [];
                    $i = 1;
                    while(($rowData = fgetcsv($handle)) !== false){
                        $objects[$i] = array_filter($rowData, function($value){
                            return !empty($value);
                        });
                        $i++;
                    }
                    ///
                    fclose($handle);
                    foreach($objects as $object){
                        $data = [
                            "name" => $object[0],
                            "brand" => strtoupper($object[1]),
                            "status" => $object[2],
                            "number" => (int) $object[3],
                            "code" => (int) $object[4],
                            "price" => (float) $object[5],
                            "category" => $object[6],
                        ];
                        $res = $this->product->save($data);
                    }
                    unlink($filepath);
                    sendResponse(True, "File received and read", $_FILES["document"]);
                } else {
                    sendResponse(False, "Unable to read file");
                }
            } else {
                sendResponse(False, "Error moving file");
            }
        } else {
            sendResponse(False, "No File");
        }
    }

    public function deleteProduct(int $id) :void 
    {
        /**
        * Call Model's method to delete product from the database 
        */

        $this->redirectIfNotLoggedIn(); 

        $res = $this->product->delete($id);

        if ($res){
            $_SESSION["success"] = "PRODUCT DELETED SUCCESSFULLY";
            redirect("/");
        } else {
            $_SESISON["message"] = "SOMETHIG WENT WRONG";
            redirect("/$id");
        }
    }

    public function showCategoryForm() :void 
    {
        /**
        * Add a new category  
        */
        $this->redirectIfNotLoggedIn(); 

        $categoryIns = new Category($this->conn);
        $categories = $categoryIns->getAll();

        $this->content = view("category", compact("categories"), $this->tplDir);
    }

    public function addCategory() :void 
    {
        /**
        * Add a new category  
        */
        $this->redirectIfNotLoggedIn(); 

        $data = []; 

        if (isset($_POST["category"]) && !empty($_POST["category"])){
            $data["name"] = strtoupper(trim($_POST["category"]));
        }

        $categoryIns = new Category($this->conn);
        $res = $categoryIns->save($data);
        if ($res){
            $_SESSION["success"] = "Category created successfully";
            redirect("/category");
        } else {
            $_SESSION["message"] = "Something went wrong";
            redirect("/category");
        }
    }

    public function deleteCategory() :void 
    {
        /**
        * Delete a category  
        */

        $this->redirectIfNotLoggedIn();

        $name = $_POST["name"];
        $id = $_POST["id"];

        $products = $this->product->getByCategory($name);

        $categoryIns = new Category($this->conn);
        $res = $categoryIns->delete($id);

        if ($res){
            if ($products){
                foreach($products as $product){
                    $action = $this->product->delete($product["id"]);
                } 
            } 
            redirect("/category");
        } else {
            $_SESSION["message"] = "Something went wrong";
        }
    }

    public function addToCart(int $id) :void 
    {
        /**
        * Add product to the cart 
        */

        $this->redirectIfNotLoggedIn();

        $prod = $this->product->get($id);

        $_SESSION["cart"][] = $prod;

        redirect("/$id");
    }

    public function removeFromCart(int $id) :void 
    {
        /**
        * Remove product from the cart 
        */

        $this->redirectIfNotLoggedIn();

        foreach ($_SESSION["cart"] as $key => $value) {
            if ($value['id'] == $id){
                unset($_SESSION["cart"][$key]);
                break;
            }
        };

        $_SESSION["cart"] = array_values($_SESSION["cart"]);

        if (getReferer($_SERVER["HTTP_REFERER"]) === "/cart"){
            redirect("/cart");
        } elseif ($_SERVER["HTTP_REFERER"] === "/checkout") {
            redirect("/checkout");
        } else {
            redirect("/$id");
        }
    }

    public function cleanCart() :void 
    {
        /**
        * Clean products in cart 
        */

        $this->redirectIfNotLoggedIn();

        $_SESSION["cart"] = []; 
        
        redirect('/');
    }

    public function searchFormSuggestions() :void  
    {
        /**
        * Request from the search form 
        * Returns a list of suggestions for the form
        * Takes the string and the value of the "search by" field
        */

        if (isset($_GET["by"]) || isset($_GET["string"])){
            $data = [
                "by" => $_GET["by"], 
                "string" => $_GET["string"]
            ]; 
            $suggests = $this->product->getByString($data);
            if (count($suggests)){
                sendResponse(True, "Finded", $suggests); 
            } else {
                sendResponse(True, "None"); 
            }
        } else {
            sendResponse(False, "Error", "No Data");
        }
    }

    public function deleteSelected() :void 
    {
        /**
        * Delete selected items 
        * Handle an AJAX request 
        */

        $data = $_POST["selected"];
        $selected = json_decode($data, true);
        

        foreach($selected as $sel){
            $id = (int) $sel;
            $this->product->delete($id);
        };
    }
   
}

