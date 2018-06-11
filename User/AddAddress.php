<?php

include_once '../config/database.php';
include_once './User.php';

$database = new Database();
$db = $database->getConnection();

$User = new User($db);

$data = json_decode(file_get_contents("php://input"));

$User->AddressID = $data->AddressID;
$User->UserID = $data->UserID;
$User->AddressName = $data->Name;
$User->AddressPhoneNo = $data->PhoneNo;
$User->Pincode = $data->Pincode;
$User->Locality = $data->Locality;
$User->Address = $data->Address;
$User->City = $data->City;
$User->State = $data->State;
$User->Landmark = $data->Landmark;
$User->Country = $data->Country;
$User->AddressType = $data->AddressType;
$User->IsActive = 1;
$User->CreatedOn = date('Y-m-d H:i:s');

$stmt = $User->AddAddress();

if($stmt)
    echo '{"key":"true"}';
else
    echo '{"key":"false"}';

?>