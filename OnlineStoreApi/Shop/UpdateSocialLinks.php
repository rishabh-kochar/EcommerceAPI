<?php

include_once '../config/database.php';
include_once './Shops.php';

$database = new Database();
$db = $database->getConnection();
 
$Shops = new Shops($db);
 
$data = json_decode(file_get_contents("php://input"));
 
$Shops->FacebookLink = $data->FL;
$Shops->YoutubeLink = $data->YL;
$Shops->InstagramLink = $data->IL;
$Shops->TwitterLink = $data->TL;
$Shops->ShopID = $data->ShopID;

$res = $Shops->UpdateSocialLinks();
if($res)
    echo '{"key":"true"}';
else
    echo '{"key":"false"}';
?>