<?php

include_once '../config/database.php';
include_once './Tracking.php';

$database = new Database();
$db = $database->getConnection();

$Tracking = new Tracking($db);

$data = json_decode(file_get_contents("php://input"));

$Tracking->OrderDetailsID = $data->OrderDetailsID;
$Tracking->TrackingText = $data->TrackingText;

$ArrivedDate = $data->ArrivedDate;
$DispatchedDate = $data->DispatchedDate;
$ArrivedTime = $data->ArrivedTime;
$DispatchedTime = $data->DispatchedTime;

// echo $ArrivedDate . "\n";
// echo $ArrivedTime . "\n";
// echo $DispatchedDate . "\n";
// echo $DispatchedTime . "\n";

$ArrivedTime = date("H:i", strtotime($ArrivedTime));
$DispatchedTime = date("H:i", strtotime($DispatchedTime));

$Tracking->ArrivedTime = date('Y-m-d H:i:s', strtotime("$ArrivedDate $ArrivedTime"));
$Tracking->DispatchedTime = date('Y-m-d H:i:s', strtotime("$DispatchedDate $DispatchedTime"));

// echo "\n" . $Tracking->ArrivedTime . "\n";
// echo "\n" . $Tracking->DispatchedTime . "\n";

// $AdminId = 1;
// $Password = "store";
//echo $Tracking->ArrivedTime . "\n";
//echo $Tracking->DispatchedTime;

$stmt = $Tracking->AddTracking();

if($stmt)
    echo '{"key":"true"}';
else
    echo '{"key":"fasle"}';

?>