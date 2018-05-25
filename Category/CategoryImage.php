<?php

include_once '../config/database.php';
include_once './Category.php';

$target_dir = "../Assets/CategoryImages/";
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777);
}

$database = new Database();
$db = $database->getConnection();
 
$Category = new Category($db);

$id = $_POST['CategoryID'];
$Category->CategoryID = $id;
$Category->CategoryName = $_POST['CategoryName'];
$Category->CategoryDesc = $_POST['CategoryDesc'];
$Category->ShopID = $_POST['ShopID'];
$Category->IsActive = 1;
$Category->CreatedOn = date('Y-m-d H:i:s');
$Category->LastUpdatedOn = date('Y-m-d H:i:s');
$Category->CategoryImage = $_POST['CategoryImage'];
$Category->CategoryImageAlt = $_POST['CategoryImageAlt'];
if(isset($_FILES['image'])){
    $CategoryImage = $_FILES['image']['name'];
}else{
    $CategoryImage = "";
}


if($id == 'new' && $CategoryImage == ''){
    echo '{"key":"Please Upload the logo."}';
}

 
$conn = $db;

if($CategoryImage != ""){

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
           $errors[]='File size must be less than 2 MB';
        }
        
        if(empty($errors)==true){
                    
            $rand = date('YmdHis');
            $newFileName = $rand . "_" . $file_name;
            move_uploaded_file($file_tmp , $target_dir . $newFileName );
            $Category->CategoryImage = $newFileName;
        }
    
}

if($id != "new" && $CategoryImage != "")
{
    if(file_exists($target_dir.$_POST['CategoryImage'])){
        unlink($target_dir.$_POST['CategoryImage']);
    }
            
}

$res = $Category->AddCategory();

echo $res;

?>