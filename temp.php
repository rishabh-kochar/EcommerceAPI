<?php

header('Allow-control-Allow-origin: *');
header('Allow-control-Allow-methods: POST');

//$data = file_get_contents("php://input");
//print_r($data) ;
//echo $data;
//echo $_POST['name'];
//$image = $data->image;
//echo $_FILES["name"];
//echo "\n\n\n\n";
//echo $image['name'];
$target_dir = "./Assets/AdminImages/"; 

//echo $_FILES['image'];
//print_r($_FILES['image']);

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
    
    if($file_size > 2097152){
       $errors[]='File size must be excately 2 MB';
    }
    
    if(empty($errors)==true){
       move_uploaded_file($file_tmp,$target_dir.$file_name);
       echo "Success";
    }else{
       print_r($errors);
    }
 }else{
     echo "fail";
 }

?>