<?php

include_once '../config/database.php';
include_once './Discount.php';

$database = new Database();
$db = $database->getConnection();

$Discount = new Discount($db);
 
//$data = json_decode(file_get_contents("php://input"));

$id = $_GET['id'];

$stmt = $Discount->GetAllShopDiscount($id);
$num = $stmt->rowcount();
if($num>0){

    $shop_arr=array();
    $shop_arr["records"]=array();
 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
        extract($row);
 
        $shop_item=array(
            "ProductID" => $ProdID,
            "ProductName" => $ProductName,
            "Price" => $Price,
            "Flat" => $Flat,
            "Percentage" => $Percentage,
            "IsActive" => $DiscountStatus
        );
 
        array_push($shop_arr["records"], $shop_item);
    }
 
    echo json_encode($shop_arr);
}else
    echo '{"key":"false"}';
?>