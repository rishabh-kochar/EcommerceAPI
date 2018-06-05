<?php

include_once '../config/database.php';
include_once './User.php';

$database = new Database();
$db = $database->getConnection();

$User = new User($db);
 
$data = json_decode(file_get_contents("php://input"));

$username = $data->username;
$password = $data->password;

//$username = "rishabhkochar58@gmail.com";
//$password = "rishabh";

$stmt = $User->CheckLogin($username,$password);
$num = $stmt->rowCount();



if($num>0){
 
    $SuperAdmin_arr=array();
   
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    extract($row);
    
    $SuperAdmin_arr=array(
        "UserID" => $UserID,
        "PhoneNO" => $PhoneNo,
        "Email" => $Email,
        "Gender" => $Gender,
        "Name" => $Name,
        "CreatedOn" => $CreatedOn
    );
        
    echo json_encode($SuperAdmin_arr);
}
 
else{
    echo '{"key" : "false"}';
}

?>