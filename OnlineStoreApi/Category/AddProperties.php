<?php

include_once '../config/database.php';
include_once './Category.php';

$database = new Database();
$db = $database->getConnection();
 
$Category = new Category($db);
 
$data = json_decode(file_get_contents("php://input"));
 
$id = $data->CategoryID;
$property = ($data->Property);


//print_r($property);

$res = $Category->AddProperties($id,$property);
echo $res;
// if($res)
//     echo '{"key":"true"}';
// else
//     echo '{"key":"false"}';
?>