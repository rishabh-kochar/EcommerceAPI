<?php

include_once '../config/database.php';
include_once './Shops.php';

$database = new Database();
$db = $database->getConnection();
 
$Shops = new Shops($db);
 
$data = json_decode(file_get_contents("php://input"));
 
$Shops->ShopName = $data->ShopName;
$Shops->ShopType = $data->ShopType;
$Shops->OwnerName = $data->OwnerName;
$Shops->Email = $data->Email;
$Shops->PhoneNo = $data->PhoneNo;
$Shops->CreatedOn = date('Y-m-d H:i:s');

$res = $Shops->SignUp();
echo $res;

?>