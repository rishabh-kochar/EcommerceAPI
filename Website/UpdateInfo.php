<?php

include_once '../config/database.php';
include_once './Website.php';

$database = new Database();
$db = $database->getConnection();

$Website = new Website($db);

$data = json_decode(file_get_contents("php://input"));

        $website->id=htmlspecialchars(strip_tags($data->id));
        $website->Name=htmlspecialchars(strip_tags($data->Name));
        $website->Logo=htmlspecialchars(strip_tags($data->Logo));
        $website->LogoAlt=htmlspecialchars(strip_tags($data->LogoAlt));
        $website->Email=htmlspecialchars(strip_tags($data->Email));
        $website->PhoneNo=htmlspecialchars(strip_tags($data->PhoneNo));
        $website->AboutUs=htmlspecialchars(strip_tags($data->AboutUs));
        $website->ContactUs=htmlspecialchars(strip_tags($data->ContactUs));
        $website->FacebookLink=htmlspecialchars(strip_tags($data->FacebookLink));
        $website->YoutubeLink=htmlspecialchars(strip_tags($data->YoutubeLink));
        $website->TwitterLink=htmlspecialchars(strip_tags($data->TwitterLink));
        $website->InstagramLink=htmlspecialchars(strip_tags($data->InstagramLink));
        $website->GSTNo=htmlspecialchars(strip_tags($data->GSTNo));
        $website->TagLine=htmlspecialchars(strip_tags($data->TagLine));
        $website->CreatedOn = date('Y-m-d H:i:s');
        $website->$lastUpdatedOn = date('Y-m-d H:i:s');


$stmt = $Website->Create($data->id);
if($stmt){
    echo '{"key":"true"}';
}else{
    echo '{"key":"false"}';
}

?>