<?php

include_once '../config/database.php';
include_once './User.php';

$database = new Database();
$db = $database->getConnection();
 
$User = new User($db);
 
$data = json_decode(file_get_contents("php://input"));
 
$User->PhoneNo = $data->PhoneNo;
$User->Email = $data->Email;
$User->UserID = $data->ID;
$User->Gender = $data->Gender;
$User->Name = $data->Name;

// $User->PhoneNo = "7622060475";
// $User->Email = "rishabhkochar58@gmail.com";
// $User->Password = "1234";
// $User->Gender = "M";
// $User->Name = "Rishabh Kochar";


$res = $User->UpdateProfile();
if($res)
    echo '{"key":"true"}';
else
    echo '{"key":"fasle"}';
?>