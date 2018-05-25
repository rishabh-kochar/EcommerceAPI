
<?php

include_once '../config/database.php';
include_once './Shops.php';

$database = new Database();
$db = $database->getConnection();

$Shops = new Shops($db);

$data = json_decode(file_get_contents("php://input"));

$username = $data->username;
$newpassword = $data->newpassword;
$verificationcode = $data->verificationcode;
//$username = "rishabhkochar58@gmail.com";
//$password = "rishabh";

$res = $Shops->ResetPassword($newpassword,$username,$verificationcode);

if($res == "0"){
    echo '{ "key" : "false"}';
}elseif($res == "1"){
    echo '{ "key" : "true"}';
}elseif($res == "2"){
    echo '{ "key" : "same"}';
}elseif($res == "3"){
    echo '{ "key" : "oldsame"}';
}
?>