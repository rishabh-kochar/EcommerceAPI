<?php

include_once '../config/database.php';
include_once './Product.php';

$database = new Database();
$db = $database->getConnection();
 
$Product = new Product($db);

//print_r($_FILES);
$data = json_decode(file_get_contents("php://input"));

$Product->ProductID = $data->ProductID;
$stock = $data->Stock;
$Product->LastStockUpdatedOn = date('Y-m-d H:i:s');
//echo $stock;

$res = $Product->AddStock($stock);

if($res)
    echo '{"key":"true"}';
else
    echo '{"key":"false"}';



?>