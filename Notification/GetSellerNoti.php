<?php

include_once '../config/database.php';
include_once './Notification.php';

$database = new Database();
$db = $database->getConnection();
 
$Notification = new Notification($db);
 
//$data = json_decode(file_get_contents("php://input"));
 

$stmt = $Notification->GetSellerNoti();
$num = $stmt->rowcount();
if($num>0){

    $shop_arr=array();
    
    $shop_arr["records"]=array();
    $Count = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
        extract($row);
 
        $shop_item=array(
            "NotificationID" => $NotificationID,
            "URL" => $URL,
            "Type" => $Type,
            "Image" => $Image,
            "Text" => $NotificationText,
            "CreatedOn" => $CreatedOn,
            "IsRead" => $IsRead
        );
 
        array_push($shop_arr["records"], $shop_item);
        $Count++;
    }
    $shop_arr["Count"] = $Count;
    echo json_encode($shop_arr);

}else{
    echo '{"key":"false"}';
}
?>