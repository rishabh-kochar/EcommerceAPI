<?php

include_once '../config/database.php';
include_once './Feedback.php';

$database = new Database();
$db = $database->getConnection();
 
$Feedback = new Feedback($db);

$data = json_decode(file_get_contents("php://input"));
$Feedback->FeedbackID = $data->FeedbackID;
$Feedback->Response = $data->Response;
$Feedback->subject = $data->subject;
$Feedback->RepliedOn =  date('Y-m-d H:i:s');

$res = $Feedback->Reply();
if($res)
    echo '{"key":"true"}';
else
    echo '{"key":"false"}';

?>