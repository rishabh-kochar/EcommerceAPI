<?php

include_once '../config/database.php';
include_once './Website.php';

$database = new Database();
$db = $database->getConnection();

$Website = new Website($db);

$data = json_decode(file_get_contents("php://input"));

        $Website->id=htmlspecialchars(strip_tags($data->Id));
        $Website->Name=htmlspecialchars(strip_tags($data->WebsiteName));
        $Website->PhoneNo=htmlspecialchars(strip_tags($data->Contact));
        $Website->AboutUs=htmlspecialchars(strip_tags($data->AboutUs));
        $Website->ContactUs=htmlspecialchars(strip_tags($data->ContactUs));
        $Website->FacebookLink=htmlspecialchars(strip_tags($data->FacebookLink));
        $Website->YoutubeLink=htmlspecialchars(strip_tags($data->YoutubeLink));
        $Website->TwitterLink=htmlspecialchars(strip_tags($data->TwitterLink));
        $Website->InstagramLink=htmlspecialchars(strip_tags($data->InstagramLink));
        $Website->GSTNo=htmlspecialchars(strip_tags($data->GSTNo));
        $Website->TagLine=htmlspecialchars(strip_tags($data->TagLine));
        $Website->CreatedOn = date('Y-m-d H:i:s');
        $Website->lastUpdatedOn = date('Y-m-d H:i:s');
        

$stmt = $Website->Create($data->Id);
if($stmt){
    echo '{"key":"true"}';
}else{
    echo '{"key":"false"}';
}

?>