<?php

include_once '../config/database.php';
include_once './Product.php';

$database = new Database();
$db = $database->getConnection();
 
$Product = new Product($db);

//print_r($_FILES);

$id = $_POST['ProductID'];


$res = $Product->DeleteImage($id);
if($res){
    echo '{"key":"true"}';
}else{
    echo '{"key":"nup"}';
}


?>