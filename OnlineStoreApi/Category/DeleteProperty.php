<?php

include_once '../config/database.php';
include_once './Category.php';

$database = new Database();
$db = $database->getConnection();
 
$Category = new Category($db);
 
$data = json_decode(file_get_contents("php://input"));
 
$id = $data->ID;
$Categoryid = $data->CID;


$res = $Category->DeleteProperty($id,$Categoryid);

?>