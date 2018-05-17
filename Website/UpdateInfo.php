<?php

include_once '../config/database.php';
include_once './Website.php';

$database = new Database();
$db = $database->getConnection();

$Website = new Website($db);

$data = json_decode(file_get_contents("php://input"));

$Email = $data->Email;
$Password = $data->Password;
$id = $data->id;

// $AdminId = 1;
// $Password = "store";  

$stmt = $Website->mailSetting();
if($stmt){
    echo '{"key":"true"}';
}else{
    echo '{"key":"false"}';
}

?>