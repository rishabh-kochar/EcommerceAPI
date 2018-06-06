<?php

include_once '../config/database.php';
include_once './Discount.php';

$database = new Database();
$db = $database->getConnection();

$Discount = new Discount($db);
 
$data = json_decode(file_get_contents("php://input"));

$Discount->ProdID = $data->ProdID;
$Discount->Flat = $data->Flat;
$Discount->Percentage = $data->Discount;
$Discount->IsActive = 1;


$res = $Discount->AddDiscount();
if($res)
    echo '{"key" : "true"}';
else
    echo '{"key" : "false"}';
?>