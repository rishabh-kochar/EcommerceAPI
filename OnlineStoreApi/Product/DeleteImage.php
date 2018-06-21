<?php

include_once '../config/database.php';
include_once './Product.php';

$database = new Database();
$db = $database->getConnection();
 
$Product = new Product($db);

//print_r($_FILES);
$data = json_decode(file_get_contents("php://input"));

$id = $data->ID;


$res = $Product->DeleteImage($id);
if($res){
    echo '{"key":"true"}';
}else{
    echo '{"key":"nup"}';
}


?>