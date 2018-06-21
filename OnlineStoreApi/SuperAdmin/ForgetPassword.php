<?php

include_once '../config/database.php';
include_once 'SuperAdmin.php';

$database = new Database();
$db = $database->getConnection();
 
$SuperAdmin = new SuperAdmin($db);
//$SuperAdmin->ReadInfo();

$data = json_decode(file_get_contents("php://input"));

$username = $data->username;

$res = $SuperAdmin->ForgetPassword($username);

if($res == "0"){
    echo json_encode('{ "key" : "false"}');
}elseif($res == "1"){
    echo json_encode('{ "key" : "true"}');
}elseif($res == "2"){
    echo json_encode('{ "key" : "nexist"}');
}

?>