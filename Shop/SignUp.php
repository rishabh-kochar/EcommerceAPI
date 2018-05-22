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

// $SuperAdmin->id = 1;
// $SuperAdmin->AdminName = "Rishi";
// $SuperAdmin->phone_no = "7622060475";
// $SuperAdmin->email = "rishabhkochar85@yahoo.com";
 

if($SuperAdmin->SignUp()){
    echo '{ "key" : "true"}';
}
else{
    echo '{ "key" : "false"}';
}
?>