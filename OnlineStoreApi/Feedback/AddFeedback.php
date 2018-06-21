<?php

include_once '../config/database.php';
include_once './Feedback.php';

$database = new Database();
$db = $database->getConnection();
 
$Feedback = new Feedback($db);

$data = json_decode(file_get_contents("php://input"));
$Feedback->Name = $data->Name;
$Feedback->Type = $data->Type;
$Feedback->Email = $data->Email;
$Feedback->Feedback = $data->Feedback;
$Feedback->CreatedOn =  date('Y-m-d H:i:s');

$res = $Feedback->AddFeedback();
if($res)
    echo '{"key":"true"}';
else
    echo '{"key":"false"}';

?>