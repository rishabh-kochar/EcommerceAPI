<?php

include_once '../config/database.php';
include_once './Category.php';

$database = new Database();
$db = $database->getConnection();
 
$Category = new Category($db);
 
$data = json_decode(file_get_contents("php://input"));
 
$Category->CategoryID = $data->CategoryID;
$Category->CategoryName = $data->CategoryName;
$Category->CategoryDesc = $data->CategoryDesc;
$Category->ShopID = $data->ShopID;
$Category->IsActive = 1;
$Category->CreatedOn = date('Y-m-d H:i:s');
$Category->LastUpdatedOn = date('Y-m-d H:i:s');

$res = $Shops->AddCategory();

if($res)
    echo '{"key":"true"}';
else
    echo '{"key":"false"}';
?>