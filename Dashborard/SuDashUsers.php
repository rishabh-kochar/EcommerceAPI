<?php

include_once '../config/database.php';
include_once './Dashborad.php';

$database = new Database();
$db = $database->getConnection();
 
$Dashboard = new Dashboard($db);

$stmt = $Dashboard->NewUsers();
$num = $stmt->rowcount();
if($num>0){
    $shop_arr=array();
    $shop_arr["records"]=array();
 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
        extract($row);
 
        $shop_item=array(
            "UserID" => $UserID,
            "Name" => $Name,
            "CreatedOn" => $database->notification_time( $CreatedOn),
            "ProfileImage" => $ProfileImage
        );
 
        array_push($shop_arr["records"], $shop_item);
    }
 
    echo json_encode($shop_arr);
}else{
    echo '{"key":"false"}';
}


?>