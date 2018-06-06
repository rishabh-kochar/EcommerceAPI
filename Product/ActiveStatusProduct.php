<?php

include_once '../config/database.php';
include_once './Product.php';

$database = new Database();
$db = $database->getConnection();
 
$Product = new Product($db);
 
$data = json_decode(file_get_contents("php://input"));
 
$id = $data->ProductID;
$Status = $data->Approved;


$res = $Product->ActiveStatusProduct($id,$Status);

if($res)
    echo '{"key":"true"}';
else
    echo '{"key":"false"}';
?>