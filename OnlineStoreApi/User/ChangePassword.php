<?php

include_once '../config/database.php';
include_once './User.php';

$database = new Database();
$db = $database->getConnection();

$User = new User($db);

$data = json_decode(file_get_contents("php://input"));

$Id = $data->ID;
$Password = $data->newpassword;
$oldpassword = $data->oldpassword;

// $AdminId = 1;
// $Password = "store";

$stmt = $User->ChangePassword($Id,$Password,$oldpassword);

echo $stmt;

?>