<?php

include_once '../config/database.php';
include_once './Product.php';

$database = new Database();
$db = $database->getConnection();

$Product = new Product($db);

//$data = json_decode(file_get_contents("php://input"));

$id = $_GET['id'];
//$id = 2;

$stmt = $Product->DiscountProduct($id);
$num = $stmt->rowCount();
 //echo $num;
// check if more than 0 record found

      
        //print_r($image_arr);


if($num>0){
 
    $shop_arr=array();
    $shop_arr["records"]=array();
 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
        extract($row);

        $shop_item=array(
            "ProductID" => $ProductId,
            "ProductName" => $ProductName,
            "Price" => $Price
        );
 
        array_push($shop_arr["records"], $shop_item);
    }
 
    echo json_encode($shop_arr);
}
 
else{
    echo '{"key":"false"}';
}

?>