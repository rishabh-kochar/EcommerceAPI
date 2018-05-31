<?php

include_once '../config/database.php';
include_once './Shops.php';

$database = new Database();
$db = $database->getConnection();
 
$Shops = new Shops($db);
 
$data = json_decode(file_get_contents("php://input"));
 
$Shops->FacebookLink = $data->FacebookLink;
$Shops->YoutubeLink = $data->YoutubeLink;
$Shops->InstagramLink = $data->InstagramLink;
$Shops->TwitterLink = $data->TwitterLink;
$Shops->ShopID = $data->id;

$res = $Shops->UpdateSocialLinks();
if($res)
    echo '{"key":"true"}';
else
    echo '{"key":"false"}';
?>