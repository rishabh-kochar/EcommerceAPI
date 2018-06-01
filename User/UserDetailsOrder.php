<?php

include_once '../config/database.php';
include_once './User.php';

$database = new Database();
$db = $database->getConnection();

$User = new User($db);

$id = $_GET['oid'];
$cid = $_GET['cid'];

$stmt = $User->UserDetailsOrder($id,$cid);
$num = $stmt->rowCount();
 //echo $num;
// check if more than 0 record found
if($num>0){
 
    // products array
    $SuperAdmin_arr=array();
   
 
    // retrieve our table contents
    
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    extract($row);

    
    $SuperAdmin_arr=array(
        "key" => "true",
        "Name" => $UserName,
        "Email" => $Email,
        "PhoneNo" => $UserPhoneNo,
        "Gender" => $Gender,
        "AddressName" => $AddressName,
        "AddressPhoneNo" => $AddressPhoneNo,
        "Pincode" => $Pincode,
        "Locality" => $Locality,
        "Address" => $Address,
        "City" => $City,
        "State" => $State,
        "LandMark" => $Landmark,
        "Country" => $Country,
        "AddressType" => $AddressType
    );
        
    echo json_encode($SuperAdmin_arr);
}
 
else{
    echo '{"key":"false"}';
}

?>