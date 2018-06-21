<?php

include_once '../config/database.php';
include_once './Shops.php';

$database = new Database();
$db = $database->getConnection();

$Shops = new Shops($db);
 
$data = json_decode(file_get_contents("php://input"));

$Shops->Username = $data->NUsername;
$Shops->Password = $data->NPassword;
$Shops->ShopID = $data->ShopID;

//$username = "rishabhkochar58@gmail.com";
//$password = "rishabh";

$stmt = $Shops->InitialSetup();
if($stmt != null){
    echo $stmt;
}else{
    echo '{"key":"false"}';
}

?>