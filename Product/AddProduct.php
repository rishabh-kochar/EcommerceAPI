<?php

include_once '../config/database.php';
include_once './Product.php';

$target_dir = "../Assets/ProductImages/";
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777);
}

$database = new Database();
$db = $database->getConnection();
 
$Product = new Product($db);
$id = $_POST['ProductID'];

if (empty($_FILES['image0']) && $id == 'new'){
    echo '{"key":"Please upload at least one image."}';
}

//print_r($_FILES);


$Product->ProductID = $id;
$Product->ProductName = $_POST['ProductName'];
$Product->ProductDesc = $_POST['ProductDesc'];
$Product->CategoryID = $_POST['CategoryID'];
$Product->IsActive = 1;
$Product->IsApproved = 1;
$Product->CreatedOn = date('Y-m-d H:i:s');
$Product->LastUpdatedOn = date('Y-m-d H:i:s');
$Product->Unit = $_POST['Unit'];
$Product->Price = $_POST['Price'];
$Product->Logoalt = $_POST['LogoAlt'];
$Product->MinStock = $_POST['MinStock'];
$Product->LogoAlt = $_POST['LogoAlt'];
$Product->ShopID = $_POST['ShopID'];

 
$conn = $db;

$res = $Product->AddProduct();
if($id != 'new'){
    $res = $id;
}

if($id != "new" &&  empty($_FILES)){
    echo '{"key":"true"}';
    exit;
}

if($res != null && !empty($_FILES)){
    $query = "SELECT count(*) as count FROM tblproductimage WHERE ProductID=:id";
    $stmt = $conn->prepare($query);
    $stmt->bindparam(":id",$res);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $size=0;
    foreach($_FILES as $key=>$tmp_name){
        $size++;
    }
    if(($row['count']+$size) >= 4){
        echo '{"key":"overflow"}';
    }else{
        $i=0;
        $j=1;
        foreach($_FILES as $key=>$tmp_name){
            $errors= array();
            $file_name = $_FILES['image'.$i]['name'];
            $file_size =$_FILES['image'.$i]['size'];
            $file_tmp =$_FILES['image'.$i]['tmp_name'];
            $file_type=$_FILES['image'.$i]['type'];
            $file_ext = explode('.',$_FILES['image'.$i]['name']);
            $file_ext = end($file_ext);
            $file_ext=strtolower($file_ext);
            
            $expensions= array("jpeg","jpg","png");
            //echo $file_name;
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
                //$Category->CategoryImage = $newFsileName;
                $query = "INSERT INTO tblproductimage(ProductID,Image) values(:id,:image)";
                $stmt = $conn->prepare($query);
                $stmt->bindparam(":id",$res);
                $stmt->bindparam(":image",$newFileName);
                $stmt->execute();
            }else{
                $j=0;
            }
            $i++;
    }
    
    if($j==1){
        echo '{"key":"true"}';
    }else{
        echo '{"key":"nup"}';
    }
}
}else{
    echo '{"key":"false"}';
}



?>