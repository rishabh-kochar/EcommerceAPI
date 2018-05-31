<?php

include_once '../config/database.php';
include_once './Shops.php';

$database = new Database();
$db = $database->getConnection();
 
$Shops = new Shops($db);
 
//$data = json_decode(file_get_contents("php://input"));
 
$id = $_GET['id'];

$stmt = $Shops->SingleShop($id);
$num = $stmt->rowcount();
if($num>0){

    $shop_arr=array();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    extract($row);
    $shop_arr=array(
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
        "IsApproved" => $IsApproved,
        "Pincode" => $Pincode
    );

    echo json_encode($shop_arr);

}else{
    echo '{"key":"false"}';
}
?>