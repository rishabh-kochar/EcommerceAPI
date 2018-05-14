<?php

include_once '../config/database.php';
include_once 'SuperAdmin.php';

$database = new Database();
$db = $database->getConnection();
 
$SuperAdmin = new SuperAdmin($db);
 
$data = json_decode(file_get_contents("php://input"));
 
$SuperAdmin->AdminId = $data->AdminId;
 
$SuperAdmin->AdminName = $data->AdminName;
$SuperAdmin->AdminImage = $data->AdminImage;
$SuperAdmin->phone_no = $data->phone_no;
$SuperAdmin->email = $data->email;
 
if($SuperAdmin->UpdateInfo()){
    echo "1";
}
else{
   echo "0";
}
?>