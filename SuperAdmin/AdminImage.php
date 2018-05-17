<?php

include_once '../config/database.php';
include_once 'SuperAdmin.php';

$database = new Database();
$db = $database->getConnection();
 
$SuperAdmin = new SuperAdmin($db);
 
$data = json_decode(file_get_contents("php://input"));
 
$SuperAdmin->AdminImage = $data->AdminImage;
$SuperAdmin->AdminId = $data->AdminId;
 
if($SuperAdmin->AdminImageUpdate()){
    echo json_encode('{ "key" : "true"}');
}
else{
    echo json_encode('{ "key" : "false"}');
}
?>