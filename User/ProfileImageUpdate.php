<?php

include_once '../config/database.php';
include_once './User.php';

$target_dir = "../Assets/UserImages/"; 
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777);
}

$database = new Database();
$db = $database->getConnection();
 
$User = new User($db);
 
//$data = json_decode(file_get_contents("php://input"));

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
    
    $expensions= array("jpeg","jpg","png");
    
    if(in_array($file_ext,$expensions)=== false){
       $errors[]="extension not allowed, please choose a JPEG or PNG file.";
    }
    
    if($file_size > 2097152000){
       $errors[]='File size must be less than 20 MB';
    }
    
    if(empty($errors)==true){
        
        $query = "SELECT * FROM tbluser WHERE UserID=:id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id",$_POST['Id']);
        $stmt->execute();
        if($stmt->rowcount()>0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($row);
            if($ProfileImage != "index.png")
                unlink($target_dir.$ProfileImage);
        }

        $rand = date('YmdHis');
       move_uploaded_file($file_tmp , $target_dir . $rand . "_" . $file_name);
       $User->ProfileImage = $rand . "_" . $file_name;
        $User->UserID = $_POST['Id'];
       if($User->UserImageUpdate()){
            echo '{"key":"true"}';
        }
        else{
            echo '{ "key" : "false" }';
        }
    }else{
        echo '{ "key" : "false" }';
    }
 }else{
    echo '{ "key" : "false" }';
 }
 

?>