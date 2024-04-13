<?php 

return [
    'driver' => 'mysql', 
    'host' => 'localhost:3306', 
    'user' => 'root', 
    'password' => '26623881', 
    'database' => 'inventoryDatabase', 
    'charset' => 'utf8',
    'pdooptions' => [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
];