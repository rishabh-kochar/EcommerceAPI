<?php

include_once '../config/database.php';
include_once './User.php';

$database = new Database();
$db = $database->getConnection();

$User = new User($db);

$data = json_decode(file_get_contents("php://input"));

$Id = $_GET['id'];
// $AdminId = 1;
// $Password = "store";

$stmt = $User->RemoveImage($Id);

if($stmt)
    echo '{"key":"true"}';
else
echo '{"key":"false"}';

?>