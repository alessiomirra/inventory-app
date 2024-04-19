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

        $data = $_POST;

        $products = $_SESSION["cart"];

        $products = $this->getAmounts($products, $data);

        $price = $this->getTotalPrice($products);

        $this->content = view("checkout", compact("products", "price"), $this->tplDir); 
    }

    public function createInvoice()  
    {
        /*
        * Create a new invoice and save new invoice object  
        */

        $data = $_POST; 
        $data["products"] = json_decode($data["products"], true); 

        $name = $this->buyerName($data);
        $fileName = $this->createFileName($data);

        $new = $this->invoice->save($name, $fileName, json_encode($data["products"]));

        if ($new){
            $data["invoice_number"] = $new;
            $newFile = $this->composeFile($data, $fileName, $name);
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

    private function getAmounts(array &$products, array $amounts) :array 
    {
        foreach ($products as &$product) { 
            foreach ($amounts as $key => $value) {
                $productId = intval(substr($key, 6));
                if ($productId === $product['id']) {
                    $product['amount'] = (int) $value;
                    break;
                }
            }
        }

        return $products;
    }

    private function getTotalPrice($items) :float 
    {
        $total = 0;

        foreach($items as $item){
            $total += $item["price"] * $item["amount"];
        }

        return $total; 
    }

    private function buyerName(array $data) :string 
    {
        if ($data["buyer"] === "person"){
            $name = $data["first-name"] ." ". $data["last-name"];
        } elseif ($data["buyer"] === "customer") {
            $name = $data["name"]; 
        }

        return $name;
    }

    private function createFileName(array $data) :string
    {
        $name = $data["buyer"] === "person" ? $data["first-name"].$data["last-name"] : str_replace(" ", "", $data["name"]);
        $filepath = "./invoices/$name.txt";
        $i = 2;

        while(file_exists($filepath)){
            $filepath = "./invoices/{$name}_$i.txt";
            $i++; 
        }

        return $filepath;
    }

    private function composeFile(array $data, string $filepath , string $name) 
    {
        $message = [
            "success" => True, 
            "message" => "File created"
        ];

        $handle = fopen($filepath, "w");

        if ($handle){

            // file header
            $file_content = "INVOICE ";
            $file_content .= "#".$data["invoice_number"]."\n \n";
            $file_content .= "BUYER: $name, " . ($data['address'] !== '' ? $data["address"] : '') . ", " . ($data['nation'] !== '' ? $data["nation"] : '') . ", " . ($data['city'] !== '' ? $data["city"] : '') . ", " . ($data['zip'] !== '' ? $data["zip"] : '') . "\n";
            if ($data["vat"] !== ""){
                $file_content .= "VAT: {$data["vat"]}\n";
            }
            if (array_key_exists("phone", $data) || array_key_exists("email", $data)){
                $file_content .= "CONTACT: ";
                $file_content .= $data["phone"] !== "" ? $data["phone"]." " : "";
                $file_content .= $data["email"] !== "" ? $data["email"]." " : "";
                $file_content .= "\n\n";
            }

            // products 
            $file_content .= "PRODUCTS \n \n";
            foreach($data["products"] as $product){
                $file_content .= "- ";
                $file_content .= $product["name"].", ";
                $file_content .= "€".$product["price"].", ";
                $file_content .= "AMOUNT: ".$product["amount"];
                $file_content .= "\n";
            }
            $file_content .= "\n";
            $file_content .= "TOTAL PRICE:  €";
            $file_content .= $data["price"]."\n\n";

            // others
            $file_content .= "PAYMENT METHOD: ";
            $file_content .= $data["payment"]."\n\n";
            
            $file_content .= "NOTES: ";

            if (array_key_exists("in_store", $data)){
                $file_content .= "in-store pick up \n";
            } elseif(array_key_exists("same_address", $data)){
                $file_content .= "same address for shipping \n";
            } else {
                $file_content .= "SHIPPING ADDRESS: ";
                $file_content .= $data["ship_address"].", ";
                $file_content .= $data["ship_nation"].", ";
                $file_content .= $data["ship_city"].", ";
                $file_content .= $data["ship_zip"]." \n";
                $file_content .= "SHIPPING NOTES: ".$data["shipping-notes"];
            }

            $file_content .= "\n\n";

            $file_content .= "TODAY: ".date("d-m-Y");
            
            fwrite($handle, $file_content); 
            fclose($handle); 

            $message = [
                "success" => True, 
                "message" => "File created", 
                "filename" => $filepath
            ];

            return $message; 

        } else {
            $message = [
                "success" => False, 
                "message" => "Unable to create file"
            ]; 

            return $message; 
        }

        return $message; 
    }

}