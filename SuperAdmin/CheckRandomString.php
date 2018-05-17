<?php
include_once '../config/database.php';
include_once 'SuperAdmin.php';

$database = new Database();
$db = $database->getConnection();
 
$SuperAdmin = new SuperAdmin($db);
//$SuperAdmin->ReadInfo();

$data = json_decode(file_get_contents("php://input"));

$rand = $data->rand;
$res = $SuperAdmin->RandomString($rand);
if($res == null){

    echo '{"key":"false"}';

}else{
    $row = $res->fetch(PDO::FETCH_ASSOC);
    extract($row);
    
    
    $new_time = date('Y-m-d H:i:s');
    //echo $RandomStringTime;
    //$RandomStringTime->modify("+2 hours");
    //$RandomStringTime->add(new DateInterval('PT2H'));
    $RandomStringTime = strtotime($RandomStringTime) + (3600*2);
    
    $new_time = strtotime($new_time);

    //echo $new_time . "\n";
    //echo $RandomStringTime . "\n";


    if($new_time < $RandomStringTime){
        $SuperAdmin_arr=array(
            "key" => "expired",
            "Email" => $Email
        );
    }else{
        $SuperAdmin_arr=array(
            "key" => "true",
            "Email" => $Email
        );
    }
    
        
    echo json_encode($SuperAdmin_arr);
}
?>