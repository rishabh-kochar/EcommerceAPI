<?php

include_once '../config/database.php';
include_once './Orders.php';

$database = new Database();
$db = $database->getConnection();
 
$Order = new Order($db);

$Order->ShopID = $_GET['id'];
$stmt = $Order->GetOrders();
$num = $stmt->rowCount();
 //echo $num;
// check if more than 0 record found
if($num>0){
 
    $shop_arr=array();
    $shop_arr["records"]=array();
 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
        extract($row);
 
        $shop_item=array(
            "OrderID" => $OrderID,
          "ProductName" => $ProductName,
          "Qty" => $Qty,
          "Price" => $Price,
          "PhoneNo" => $PhoneNo,
          "City" => $City,
          "Status" => $Status,
          "ProductID" => $PID,
          "OrderDetailID" => $OrderDetailID,
          "CustomerID" => $CID

        );
 
        array_push($shop_arr["records"], $shop_item);
    }
 
    echo json_encode($shop_arr);
}
 
else{
    echo '{"key":"false"}';
}

?>