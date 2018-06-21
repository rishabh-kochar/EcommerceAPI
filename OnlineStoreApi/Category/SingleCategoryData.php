<?php

include_once '../config/database.php';
include_once './Category.php';

$database = new Database();
$db = $database->getConnection();

$Category = new Category($db);

$id = $_GET['id'];
$stmt = $Category->SingleCategoryData($id);
$num = $stmt->rowCount();
 //echo $num;
// check if more than 0 record found
if($num>0){
 
    $shop_arr=array();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    extract($row);
        $shop_arr=array(
            "CategoryID" => $CategoryId,
            "CategoryName" => $CategoryName,
            "CategoryDesc" => $CategoryDesc,
            "CategoryImage" => $CategoryImage,
            "CategoryImageAlt" => $CategoryImageAlt,
            "ShopID" => $ShopID,
            "IsActive" => $IsActive,
            "CreatedOn" => $CreatedOn,
            "LastUpdatedOn" => $LastUpdatedOn,
            "IsApproved" => $IsApproved

        );
 
       
 
    echo json_encode($shop_arr);
}
 
else{
    $shop_arr=array();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    extract($row);
        $shop_arr=array(
            "CategoryID" => "",
            "CategoryName" => "",
            "CategoryDesc" => "",
            "CategoryImage" => "",
            "CategoryImageAlt" => "",
            "ShopID" => "",
            "IsActive" => "",
            "CreatedOn" => "",
            "LastUpdatedOn" => ""

        );
    echo json_encode($shop_arr);
}

?>