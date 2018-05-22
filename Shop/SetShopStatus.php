<?php

include_once '../config/database.php';
include_once './Shops.php';

$database = new Database();
$db = $database->getConnection();
 
$Shops = new Shops($db);
 
$data = json_decode(file_get_contents("php://input"));
 
$id = $data->ShopID;
$status = $data->Status;

$res = $SuperAdmin->SetShopStatus($id,$status); 
if($res) {
    echo '{ "key" : "true"}';
}else{
    echo '{ "key" : "false"}';
}
?>