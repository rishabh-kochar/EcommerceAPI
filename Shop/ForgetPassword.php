<?php

include_once '../config/database.php';
include_once './Shops.php';

$database = new Database();
$db = $database->getConnection();
 
$Shops = new Shops($db);
//$SuperAdmin->ReadInfo();

$data = json_decode(file_get_contents("php://input"));

$username = $data->username;

$res = $Shops->ForgetPassword($username);

if($res == "0"){
    echo '{ "key" : "false"}';
}elseif($res == "1"){
    echo '{ "key" : "true"}';
}elseif($res == "2"){
    echo '{ "key" : "nexist"}';
}

?>