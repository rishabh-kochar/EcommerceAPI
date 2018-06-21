<?php

include_once '../config/database.php';
include_once './Category.php';

$database = new Database();
$db = $database->getConnection();

$Category = new Category($db);

//$data = json_decode(file_get_contents("php://input"));

$id = $_GET['id'];
$ProductID = $_GET['pid'];


$stmt = $Category->GetProperties($id,$ProductID);
if(!$stmt)
    echo '{"key":"false"}';
else
    echo $stmt;

?>