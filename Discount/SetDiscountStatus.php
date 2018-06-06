<?php

include_once '../config/database.php';
include_once './Discount.php';

$database = new Database();
$db = $database->getConnection();

$Discount = new Discount($db);
 
$data = json_decode(file_get_contents("php://input"));

$ID = $data->ProdID;
$Status = $data->Status;



$res = $Discount->SetDiscountStatus($ID,$Status);
if($res)
    echo '{"key" : "true"}';
else
    echo '{"key" : "false"}';
?>