<?php

include_once '../config/database.php';
include_once './Shops.php';

$database = new Database();
$db = $database->getConnection();

$Shops = new Shops($db);
 
$data = json_decode(file_get_contents("php://input"));

$username = $data->username;
$password = $data->password;

//$username = "rishabhkochar58@gmail.com";
//$password = "rishabh";

$stmt = $Shops->CheckLogin($username,$password);
if($stmt != null){
    $num = $stmt->rowCount();
}else{
    $num = 0;
}


if($num>0){
 
    $SuperAdmin_arr=array();
   
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    extract($row);
    
    $SuperAdmin_arr=array(
        "ShopID" => $ShopID,
            "ShopName" => $ShopName,
            "Tagline" => $Tagline,
            "LogoImage" => $LogoImage,
            "Address" => $Address,
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
            "IsActive" => $IsActive,
            "CreatedOn" => $CreatedOn,
            "ShopType" => $ShopType,
            "ApprovedOn" => $ApprovedOn,
            "IsSessionActive" => $IsSessionActive,
            "IsInitialSetup" => $IsInitialSetup
    );
        
    echo json_encode($SuperAdmin_arr);
}
 
else{
    echo '{"key" : "false"}';
}

?>