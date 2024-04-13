<?php 

namespace App\Controllers; 

use PDO; 

use App\Models\Invoice;
use App\Models\Product;

class InvoiceController extends BaseController  
{
    protected Invoice $invoice;

    public function __construct(
        protected ?PDO $conn
    ){
        parent::__construct($conn);

        $this->invoice = new Invoice($conn); 
    }

    public function invoices() :void 
    {   
        /**
        * Invoices List Page  
        */

        $this->redirectIfNotLoggedIn();

        $params = [];
        $params["limit"] = getConfig("recordsPerPage");

        // order by 
        $orderBy = getParam("order-by") ? getParam("order-by") : "id";
        $orderDir = getParam("order-dir") ? getParam("order-dir") : "DESC";
        $params["order-by"] = $orderBy;
        $params["order-dir"] = $orderDir;

        // search
        $search = !empty($_REQUEST["search"]) ? $_REQUEST["search"] : "";
        $params["search"] = $search; 

        // pagination
        $page = getParam("page") ? (int) getParam("page") : 1; 
        $start = $params["limit"] * ($page - 1);
        if ($start < 0){
            $start = 0;
        }
        $params["start"] = $start;

        $count = $this->invoice->count();
        $invoices = $this->invoice->getAll($params);

        $this->content = view("invoices", compact("count", "invoices"), $this->tplDir);
    }

    public function checkout() :void 
    {   
        /**
        * Checkout Page  
        */

        $this->redirectIfNotLoggedIn(); 

        $products = $_SESSION["cart"];

        $price = getTotalPrice($_SESSION["cart"]);

        $this->content = view("checkout", compact("products", "price"), $this->tplDir); 
    }

    public function createInvoice()  
    {
        /*
        * Create a new invoice and save new invoice object  
        */

        $data = $_POST; 
        $data["products"] = json_decode($data["products"], true); 

        $name = buyerName($data);
        $fileName = createFileName($data);

        $new = $this->invoice->save($name, $fileName);

        if ($new){
            $data["invoice_number"] = $new;
            $newFile = composeFile($data, $fileName, $name);
            if ($newFile["success"]){
                $prod = new Product($this->conn);
                if ($prod->decreaseProducts($data["products"])){
                        // clean cart
                        $_SESSION["cart"] = [];

                        // return message
                        sendResponse(True, "Invoice Created");
                } else {
                        sendResponse(False, "Something went wrong");
                }
            } else {
                sendResponse(False, "Something went wrong");
            }
        } else {
            sendResponse(False, "Something went wrong");
        }
    }
}