<?php

include_once '../config/database.php';
include_once './Product.php';

$database = new Database();
$db = $database->getConnection();

$Product = new Product($db);

$data = json_decode(file_get_contents("php://input"));

$id = $data->CategoryID;
$search = $data->Search;
$Min = $data->Min;
$Max = $data->Max;
$property = $data->Data;

$res = $Product->FilterData($id,$search,$Min,$Max,$property);
echo $res;

?>