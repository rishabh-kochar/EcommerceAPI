<?php

include_once '../config/database.php';
include_once './Notification.php';

$database = new Database();
$db = $database->getConnection();
 
$Notification = new Notification($db);
 
//$data = json_decode(file_get_contents("php://input"));
 
$id = $_GET['id'];

$res = $Notification->ClearAllNotification($id); 
if($res) 
    echo '{ "key" : "true"}';
else
    echo '{ "key" : "false"}';

?>