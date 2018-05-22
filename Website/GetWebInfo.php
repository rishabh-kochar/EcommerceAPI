<?php

include_once '../config/database.php';
include_once './Website.php';

$database = new Database();
$db = $database->getConnection();

$Website = new Website($db);

//$data = json_decode(file_get_contents("php://input"));

//$id = 1;

$stmt = $Website->GetWebInfo();
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
        "Id" => $id,
        "Name" => $Name,
        "PhoneNo" => $PhoneNo,
        "AboutUs" => $AboutUs,
        "ContactUs" => $ContactUs,
        "FacebookLink" => $FacebookLink,
        "TwitterLink" => $TwitterLink,
        "InstagramLink" => $InstagramLink,
        "YoutubeLink" => $YoutubeLink,
        "GSTNo" => $GSTNo,
        "CreatedOn" => $CreatedOn,
        "LastUpdatedOn" => $LastUpdatedOn,
        "TagLine" => $TagLine
    );
        
    echo json_encode($SuperAdmin_arr);
}
 
else{
    echo '{"key":"false"}';
}

?>