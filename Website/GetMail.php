<?php

include_once '../config/database.php';
include_once './Website.php';

$database = new Database();
$db = $database->getConnection();

$Website = new Website($db);

$stmt = $Website->ReadEmail(1);
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
        "Email" => $Email
    );
        
    echo json_encode($SuperAdmin_arr);
}
 
else{
    echo '{"key":"false"}';
}

?>