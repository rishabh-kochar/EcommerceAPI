<?php

include_once '../config/database.php';
include_once 'SuperAdmin.php';

$database = new Database();
$db = $database->getConnection();

$SuperAdmin = new SuperAdmin($db);

$data = json_decode(file_get_contents("php://input"));

$AdminId = $data->$AdminId;
$Password = $data->$Password;

// $AdminId = 1;
// $Password = "store";  

$stmt = $SuperAdmin->ChangePassword($AdminId,$Password);

echo $stmt;

?>