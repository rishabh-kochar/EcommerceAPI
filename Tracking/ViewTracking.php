<?php

include_once '../config/database.php';
include_once './Tracking.php';

$database = new Database();
$db = $database->getConnection();
 
$Tracking = new Tracking($db);
$id = $_GET['id'];
if(isset($_GET['sid'])){
    $sid = $_GET['sid'];
}else{
    $sid=0;
}
//$id = 4;

$stmt = $Tracking->ViewTracking($id,$sid);
$num = $stmt->rowCount();
 //echo $num;
// check if more than 0 record found
if($num>0){
 
    $shop_arr=array();
    $shop_arr["records"]=array();
    $i=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
        extract($row);
        $ArrivedTime= $database->notification_time( $ArrivedTime);
        $DispatchedTime= $database->notification_time( $DispatchedTime);
        $shop_item=array(
            "No" => $i++,
          "TrackingID" => $TrackingID,
          "Text" => $TrackingText,
          "ArrivedTime" => $ArrivedTime,
          "DispatchedTime" => $DispatchedTime,
          "Status" => $Status

        );
 
        array_push($shop_arr["records"], $shop_item);
    }
 
    echo json_encode($shop_arr);
}
 
else{
    echo '{"key":"false"}';
}

?>