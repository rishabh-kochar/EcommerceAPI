<?php

include_once '../config/database.php';
include_once './Tracking.php';

$database = new Database();
$db = $database->getConnection();

$Tracking = new Tracking($db);

$data = json_decode(file_get_contents("php://input"));

$Tracking->OrderDetailsID = $data->OrderDetailsID;
$Tracking->TrackingText = $data->TrackingText;
$Tracking->ArrivedTime = $data->ArrivedTime;
$Tracking->DispatchedTime = $data->DispatchedTime;

// $AdminId = 1;
// $Password = "store";

$stmt = $Tracking->AddTracking();

if($stmt)
    echo '{"key":"true"}';
else
    echo '{"key":"fasle"}';

?>