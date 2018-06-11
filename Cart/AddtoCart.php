<?php

include_once '../config/database.php';
include_once './Cart.php';

$database = new Database();
$db = $database->getConnection();
 
$Cart = new Cart($db);

//print_r($_FILES);
$data = json_decode(file_get_contents("php://input"));

$Cart->ProductID = $data->ProductID;
$Cart->UserID = $data->UserID;
$Cart->Qty = $data->Qty;
$Cart->AddedOn = date('Y-m-d H:i:s');
//echo $stock;

$res = $Cart->AddToCart();

if($res)
    echo '{"key":"true"}';
else
    echo '{"key":"false"}';



?>