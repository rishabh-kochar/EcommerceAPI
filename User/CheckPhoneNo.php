<?php

include_once '../config/database.php';
include_once './User.php';

$database = new Database();
$db = $database->getConnection();

$User = new User($db);
 
$data = json_decode(file_get_contents("php://input"));

$username = $data->PhoneNo;

//$username = "rishabhkochar58@gmail.com";
//$password = "rishabh";

$stmt = $User->CheckPhoneNo($username);
$num = $stmt->rowCount();

if($num>0)
    echo '{"key" : "true"}';
else
    echo '{"key" : "false"}';

?>