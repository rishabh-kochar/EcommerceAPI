<?php

include_once '../config/database.php';
include_once './Shops.php';

$database = new Database();
$db = $database->getConnection();

$Shops = new Shops($db);

$data = json_decode(file_get_contents("php://input"));

$Id = $data->ShopID;
$Password = $data->newpassword;
$oldpassword = $data->oldpassword;

// $AdminId = 1;
// $Password = "store";

$stmt = $Shops->ChangePassword($Id,$Password,$oldpassword);

echo $stmt;

?>