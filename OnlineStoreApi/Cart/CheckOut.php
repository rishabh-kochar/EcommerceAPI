<?php

include_once '../config/database.php';
include_once './Cart.php';

$database = new Database();
$db = $database->getConnection();
 
$Cart = new Cart($db);

$data = json_decode(file_get_contents("php://input"));

$id = $data->UserID;
$Addid = $data->AddressID;
$Amt = $data->TotalAmount;


$res = $Cart->CheckOut($id,$Addid,$Amt);
echo $res;

?>