<?php

include_once '../config/database.php';
include_once './Discount.php';

$database = new Database();
$db = $database->getConnection();

$Discount = new Discount($db);
 
$data = json_decode(file_get_contents("php://input"));

$id = $data->id;
$Discount->ProdID = $data->ProdID;
$Discount->Flat = $data->Flat;
$Discount->Percentage = $data->Percentage;
$Discount->IsActive = 1;
$Discount->CreatedOn = date('Y-m-d H:i:s');
$Discount->UpdatedOn = date('Y-m-d H:i:s');


$res = $Discount->AddDiscount($id);
if($res)
    echo '{"key" : "true"}';
else
    echo '{"key" : "false"}';
?>