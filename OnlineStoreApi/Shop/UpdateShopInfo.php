<?php

include_once '../config/database.php';
include_once './Shops.php';

$database = new Database();
$db = $database->getConnection();
 
$Shops = new Shops($db);
 
$data = json_decode(file_get_contents("php://input"));
 
$Shops->ShopName = $data->ShopName;
$Shops->ShopType = $data->ShopType;
$Shops->Email = $data->Email;
$Shops->PhoneNo = $data->Contact;
$Shops->Address = $data->Address;
$Shops->City = $data->City;
$Shops->State = $data->State;
$Shops->Pincode = $data->Pincode;
$Shops->ShopID = $data->ShopID;

$res = $Shops->UpdateShopInfo();
if($res)
    echo '{"key":"true"}';
else
    echo '{"key":"false"}';
?>