<?php

include_once '../config/database.php';
include_once './Shops.php';

$target_dir = "../Assets/ShopLogo/"; 
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777);
}

$database = new Database();
$db = $database->getConnection();
 
$Shops = new Shops($db);
 
//$data = json_decode(file_get_contents("php://input"));
 
$Shops->GSTNo = $_POST['GSTNo'];
$Shops->LogoImage = $_POST['LogoImage'];
$Shops->LogoAlt = $_POST['LogoAlt'];
$Shops->Tagline = $_POST['TagLine'];
$Shops->ShopID = $_POST['ShopID'];

$conn = $db;
 

if(isset($_FILES['image'])){
    $errors= array();
    $file_name = $_FILES['image']['name'];
    $file_size =$_FILES['image']['size'];
    $file_tmp =$_FILES['image']['tmp_name'];
    $file_type=$_FILES['image']['type'];
    $file_ext = explode('.',$_FILES['image']['name']);
    $file_ext = end($file_ext);
    $file_ext=strtolower($file_ext);
    
    // $new_time = date('Y-m-d H:i:s');
    // $new_time = strtotime($new_time);
    // $file_name = "'" . $new_time . "'" + $file_name;
    $expensions= array("jpeg","jpg","png");
    
    if(in_array($file_ext,$expensions)=== false){
       $errors[]="extension not allowed, please choose a JPEG or PNG file.";
    }
    
    if($file_size > 2097152000){
       $errors[]='File size must be less than 20 MB';
    }
    
    if(empty($errors)==true){
        

        // $file_name = "adminImage_" . $_POST['AdminId'] . $ImageExt;
        //$file_name = "adminImage_" . $_POST['AdminId'] . "." . $file_ext;
        //echo $target_dir . $file_name;
        $rand = date('YmdHis');
       move_uploaded_file($file_tmp , $target_dir . $rand . "_" . $file_name);
       $Shops->LogoImage = $rand . "_" . $file_name;
      
    }else{
        echo '{ "key" : "nup" }';
    }
 }

 if($Shops->UpdateBusinessDetails()){
    echo '{"key":"true"}';
}
else{
    echo '{ "key" : "false" }';
}

?>