<?php

include_once '../config/database.php';
include_once './Shops.php';

$database = new Database();
$db = $database->getConnection();

$Shops = new Shops($db);

$stmt = $Shops->ShopData();
$num = $stmt->rowCount();
 //echo $num;
// check if more than 0 record found
if($num>0){
 
    $shop_arr=array();
    $shop_arr["records"]=array();
 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
        extract($row);
 
        $shop_item=array(
            "ShopID" => $ShopID,
            "ShopName" => $ShopName,
            "Tagline" => $Tagline,
            "LogoImage" => $LogoImage,
            "Address" => $Address,
            "City" => $City,
            "State" => $State,
            "PhoneNo" => $PhoneNo,
            "Email" => $Email,
            "Website" => $Website,
            "OwnerName" => $OwnerName,
            "FacebookLink" => $FacebookLink,
            "InstagramLink" => $InstagramLink,
            "TwitterLink" => $TwitterLink,
            "YoutubeLink" => $YoutubeLink,
            "LogoAlt" => $LogoAlt,
            "GSTNo" => $GSTNo,
            "UserName" => $UserName,
            "Password" => $Password,
            "OldPassword" => $OldPassword,
            "PasswordUpdatedOn" => $PasswordUpdatedOn,
            "IsActive" => $IsActive,
            "CreatedOn" => $CreatedOn,
            "ShopType" => $ShopType,
            "ApprovedOn" => $ApprovedOn,
            "IsSessionActive" => $IsSessionActive,
            "IsApproved" => $IsApproved
        );
 
        array_push($shop_arr["records"], $shop_item);
    }
 
    echo json_encode($shop_arr);
}
 
else{
    echo '{"key":"false"}';
}

?>