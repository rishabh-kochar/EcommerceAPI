<?php

include_once '../config/database.php';
include_once './Discount.php';

$database = new Database();
$db = $database->getConnection();

$Discount = new Discount($db);
 
$data = json_decode(file_get_contents("php://input"));

$id = $_GET['id'];


$stmt = $Discount->getSingleProductDiscount($id);
$num = $stmt->rowcount();
if($num>0){

  
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        extract($row);
        $shop_arr=array(
            "key" => "true",
            "ProductID" => $ProdID,
            "ProductName" => $ProductName,
            "Price" => $Price,
            "Flat" => $Flat,
            "Percentage" => $Percentage,
            "IsActive" => $DiscountStatus
        );
 
 
    echo json_encode($shop_arr);
}else
    echo '"key":"false"';
?>