<?php

include_once '../config/database.php';
include_once './User.php';

$database = new Database();
$db = $database->getConnection();
 
$User = new User($db);
 
//$data = json_decode(file_get_contents("php://input"));
 
$id = $_GET['id'];

$stmt = $Shops->UserData($id);
$num = $stmt->rowcount();
if($num>0){

    $shop_arr=array();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    extract($row);
    $shop_arr=array(
        "UserID" => $UserID,
            "PhoneNO" => $PhoneNo,
            "Email" => $Email,
            "Gender" => $Gender,
            "Name" => $Name,
            "CreatedOn" => $CreatedOn,
            "IsActive" => $IsActive,
            "ProfileImage" => $ProfileImage
    );

    echo json_encode($shop_arr);

}else{
    echo '{"key":"false"}';
}
?>