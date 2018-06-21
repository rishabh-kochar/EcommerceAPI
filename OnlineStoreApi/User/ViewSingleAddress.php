<?php

include_once '../config/database.php';
include_once './User.php';

$database = new Database();
$db = $database->getConnection();

$User = new User($db);

$id = $_GET['id'];

$stmt = $User->ViewAllAddress($id);
$num = $stmt->rowCount();
 //echo $num;
// check if more than 0 record found
if($num>0){

   
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        extract($row);
 
        $shop_item=array(
            "AddressID" => $AddressID,
            "UserID" => $UserID,
            "AddressName" => $Name,
            "AddressPhoneNo" => $PhoneNo,
            "Pincode" => $Pincode,
            "Locality" => $Locality,
            "Address" => $Address,
            "City" => $City,
            "State" => $State,
            "LandMark" => $Landmark,
            "Country" => $Country,
            "AddressType" => $AddressType
           
        );
 

 
    echo json_encode($shop_item);
}
 
else{
    echo '{"key":"true"}';
}

?>