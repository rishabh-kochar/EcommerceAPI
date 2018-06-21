<?php

include_once '../config/database.php';
include_once './Feedback.php';

$database = new Database();
$db = $database->getConnection();
 
$Feedback = new Feedback($db);

$stmt = $Feedback->GetRepliedFeedback();
$num = $stmt->rowCount();
 //echo $num;
// check if more than 0 record found
if($num>0){
 
    $shop_arr=array();
    $shop_arr["records"]=array();
 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
        extract($row);
 
        $shop_item=array(
          "FeedbackID" => $FeedbackID,
          "Name" => $Name,
          "Email" => $Email,
          "Type" => $Type,
          "Feedback" => $Feedback,
          "CreatedOn" => $CreatedOn,
          "Reply" => $Reply,
          "RepliedOn" => $RepliedOn
        );
 
        array_push($shop_arr["records"], $shop_item);
    }
 
    echo json_encode($shop_arr);
}
 
else{
    echo '{"key":"false"}';
}

?>