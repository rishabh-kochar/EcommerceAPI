<?php

include_once '../config/database.php';
include_once './User.php';

$database = new Database();
$db = $database->getConnection();
 
$User = new User($db);
 
$data = json_decode(file_get_contents("php://input"));
 
$id = $data->ID;
$status = $data->Status;

$res = $User->SetStatus($id,$status); 
if($res) {
    echo '{ "key" : "true"}';
}else{
    echo '{ "key" : "false"}';
}
?>