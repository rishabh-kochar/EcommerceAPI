<?php

include_once '../config/database.php';
include_once 'SuperAdmin.php';

$database = new Database();
$db = $database->getConnection();
 
$SuperAdmin = new SuperAdmin($db);
 
$data = json_decode(file_get_contents("php://input"));
 
$SuperAdmin->id = $data->AdminId;
$SuperAdmin->email = $data->email;
$SuperAdmin->phone_no = $data->phone_no;
$SuperAdmin->AdminName = $data->AdminName;

// $SuperAdmin->id = 1;
// $SuperAdmin->AdminName = "Rishi";
// $SuperAdmin->phone_no = "7622060475";
// $SuperAdmin->email = "rishabhkochar85@yahoo.com";
 

if($SuperAdmin->UpdateInfo()){
    echo '{ "key" : "true"}';
}
else{
    echo '{ "key" : "false"}';
}
?>