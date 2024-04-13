<?php 
use App\Core\Router;
use App\Controllers\BaseController;
use App\Controllers\ProductController;
use App\Controllers\InvoiceController;
use App\DB\DbFactory; 

chdir(dirname(__DIR__));

require_once 'core/bootstrap.php';

$pageUrl = $_SERVER["PHP_SELF"];

$data = require "config/database.php";
$appConfig = require "config/app.config.php";

$router = new Router($appConfig["routes"]);

$arrController = $router->dispatch();
$controllerParams = $arrController[2] ?? [];

try{
    $conn = (DbFactory::create($data))->getConn();

    $controllerClass = $arrController[0]; 
    $method = $arrController[1]; 

    $controller = new $controllerClass($conn); 

    if (method_exists($controller, $method)){
        $controller->$method(...$controllerParams);
    }

    if($controller instanceof BaseController){
        $controller->display();
    }
} catch(Exception $e){
    var_dump($e->getMessage());
}