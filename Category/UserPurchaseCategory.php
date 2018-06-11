<?php

include_once '../config/database.php';
include_once './Category.php';

$database = new Database();
$db = $database->getConnection();

$Category = new Category($db);

//$data = json_decode(file_get_contents("php://input"));

$stmt = $Category->ShopAllCategory();
$num = $stmt->rowCount();
 //echo $num;
// check if more than 0 record found
if($num>0){
 
    $shop_arr=array();
    $shop_arr["records"]=array();
 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
        extract($row);
 
        $shop_item=array(
            "CategoryID" => $CategoryId,
            "CategoryName" => $CategoryName,
            "CategoryDesc" => $CategoryDesc,
            "CategoryImage" => $CategoryImage,
            "CategoryImageAlt" => $CategoryImageAlt

        );
 
        array_push($shop_arr["records"], $shop_item);
    }
 
    echo json_encode($shop_arr);
}
 
else{
    echo '{"key":"false"}';
}

?>