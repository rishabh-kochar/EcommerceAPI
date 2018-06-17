<?php

include_once '../config/database.php';
include_once 'SuperAdmin.php';

$target_dir = "../Assets/AdminImages/"; 
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777);
}

$database = new Database();
$db = $database->getConnection();
 
$SuperAdmin = new SuperAdmin($db);
 
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
    
    // $new_time = date('Y-m-d H:i:s');
    // $new_time = strtotime($new_time);
    // $file_name = "'" . $new_time . "'" + $file_name;
    $expensions= array("jpeg","jpg","png");
    
    if(in_array($file_ext,$expensions)=== false){
       $errors[]="extension not allowed, please choose a JPEG or PNG file.";
    }
    
    if($file_size > 2097152){
       $errors[]='File size must be less than 2 MB';
    }
    
    if(empty($errors)==true){
        
        $query = "SELECT * FROM tbladmin WHERE AdminId=:adminid";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":adminid",$_POST['AdminId']);
        $stmt->execute();
        if($stmt->rowcount()>0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($row);
            unlink($target_dir.$AdminImage);
        }

        // $file_name = "adminImage_" . $_POST['AdminId'] . $ImageExt;
        //$file_name = "adminImage_" . $_POST['AdminId'] . "." . $file_ext;
        //echo $target_dir . $file_name;
        $rand = date('YmdHis');
       move_uploaded_file($file_tmp , $target_dir . $rand . "_" . $file_name);
       $SuperAdmin->AdminImage = $rand . "_" . $file_name;
        $SuperAdmin->id = $_POST['AdminId'];
       if($SuperAdmin->AdminImageUpdate()){
            echo '{"key":"true"}';
        }
        else{
            echo '{ "key" : "1false" }';
        }
    }else{
        echo '{ "key" : "2false" }';
    }
 }else{
    echo '{ "key" : "3false" }';
 }
 

?>