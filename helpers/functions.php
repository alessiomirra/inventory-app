<?php 

$configs = require "./config/app.config.php";


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

function sendAPIRequest($data, $path="/")
{
    /**
    * This function handle the request to the Python API
    * to handle our inventory data 
    */

    $url = $configs["api"]["localhost"].$configs["api"]["port"].$path;

    $data["token"] = $configs["api"]["key"];
    $payload = json_encode($data);

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

    $response = curl_exec($curl);

    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);


    curl_close($curl);

    return $httpCode == 200;
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