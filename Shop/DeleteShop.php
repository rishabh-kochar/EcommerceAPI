<?php

include_once '../config/database.php';
include_once './Shops.php';

$database = new Database();
$db = $database->getConnection();
 
$Shops = new Shops($db);
 
$data = json_decode(file_get_contents("php://input"));
 
$id = $data->ShopID;

$res = $Shops->DeleteShop($id); 
if($res) {
    echo '{ "key" : "true"}';
}else{
    echo '{ "key" : "false"}';
}
?>