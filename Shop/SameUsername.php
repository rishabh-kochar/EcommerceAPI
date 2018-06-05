<?php

include_once '../config/database.php';
include_once './Shops.php';

$database = new Database();
$db = $database->getConnection();

$Shops = new Shops($db);
 
$data = json_decode(file_get_contents("php://input"));

$username = $data->username;

//$username = "rishabhkochar58@gmail.com";
//$password = "rishabh";

$stmt = $Shops->SameUsername($username);
$num = $stmt->rowCount();

if($num>0)
    echo '{"key" : "true"}';
else
    echo '{"key" : "false"}';

?>