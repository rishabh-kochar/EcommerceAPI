<?php

include_once '../config/database.php';
include_once 'SuperAdmin.php';

$database = new Database();
$db = $database->getConnection();

$SuperAdmin = new SuperAdmin($db);

$data = json_decode(file_get_contents("php://input"));

$username = $data->username;
$password = $data->password;

//$username = "rishabhkochar58@gmail.com";
//$password = "rishabh";

$stmt = $SuperAdmin->CheckLogin($username,$password);
$num = $stmt->rowCount();

if($num>0){
 
    // products array
    $SuperAdmin_arr=array();
   
 
    // retrieve our table contents
    
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    extract($row);
    
    $SuperAdmin_arr=array(
        "Adminid" => $AdminId,
        "Adminname" => $AdminName,
        "AdminImage" => $AdminImage,
        "PhoneNo" => $PhoneNo,
        "Email" => $Email,
        "Password" => $Password,
        "OldPassword" => $OldPassword,
        "CreatedOn" => $CreatedOn,
        "PasswordUpdatedOn" => $PasswordUpdatedOn
    );
        
    echo json_encode($SuperAdmin_arr);
}
 
else{
    echo "0";
}

?>