<?php 

function dd(...$data): void {
    var_dump($data);
    die;
}

function getConfig($param)
{
    // works with "configs" key only

    $config = require "./config/app.config.php";

    return $config["configs"][$param] ? $config["configs"][$param] : null;
}

function redirect(string $url = '/'): void {
    header("Location:$url");
    exit();
}

function sendResponse($result, $message, $data=""){
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $result,
        'message' => $message,
        'data' => $data  
    ]);
    exit;
}

function getReferer(string $url) :string  
{
    $parsed_url = parse_url($url);

    return $parsed_url["path"];
}

function view(string $view, array $data = [], string $viewDir = 'app/Views/'): string {
    extract($data, EXTR_OVERWRITE);

    ob_start();
        require $viewDir . $view.'.tpl.php';
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
}

function getParam($param, $default=null)
{
    return !empty($_REQUEST[$param]) ? $_REQUEST[$param] : $default;
}

function isUserLoggedIn(): bool {
    return $_SESSION['loggedin'] ?? false;
}

function formatDate($date){
    $formatted = $date; 
    if (strtotime($formatted) !== false){ // date is in the right format
        $formatted = $date; 
    } else {
        $right_date = date('Y-m-d', strtotime($formatted));
    }

    return $formatted; 
}

function showStatus($status)
{
    if ($status === "in_stock"){
        return "IN STOCK"; 
    } else if ($status === "out_stock"){
        return "OUT OF STOCK"; 
    } else {
        return "ARRIVING";
    }
}

function remainingColor(int $number) :string 
{
    if ($number <= 5){
        return "text-danger";
    } 

    return "text-black"; 
}

function getTotalPrice(array $items) :float 
{   
    $total = 0;

    foreach($items as $item){
        $total += $item["price"];
    }

    return $total; 
}

function buyerName(array $data) :string 
{
    if ($data["buyer"] === "person"){
        $name = $data["first-name"] ." ". $data["last-name"];
    } elseif ($data["buyer"] === "customer") {
        $name = $data["name"]; 
    }

    return $name;
}

function createFileName(array $data) :string
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

function composeFile(array $data, string $filepath , string $name) 
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
            $file_content .= "€".$product["price"];
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

function getStatus(string $search) :string 
{
    if ($search === "IN STOCK"){
        return "in_stock";
    } elseif ($search === "OUT OF STOCK") {
        return "out_stock";
    } else {
        return "arriving";
    }
}
