
<?php

include_once '../config/database.php';
include_once 'SuperAdmin.php';

$database = new Database();
$db = $database->getConnection();

$SuperAdmin = new SuperAdmin($db);

$data = json_decode(file_get_contents("php://input"));

$username = $data->username;
$newpassword = $data->newpassword;
$verificationcode = $data->verificationcode;
//$username = "rishabhkochar58@gmail.com";
//$password = "rishabh";

$res = $SuperAdmin->ResetPassword($newpassword,$username,$verificationcode);

if($res == "0"){
    echo json_encode('{ "key" : "false"}');
}else{
    echo json_encode('{ "key" : "true"}');
}

?>