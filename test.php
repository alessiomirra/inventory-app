<?php 

$localhost = "http://127.0.0.1:";
$port = "5000";
$path = "/get";

$url = $localhost.$port.$path;

//$url = "http://127.0.0.1:5000/get";


$data = [
    "message" => "Testing Connection"
];

$payload = json_encode($data);

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

$response = curl_exec($curl);
curl_close($curl);

echo $response;