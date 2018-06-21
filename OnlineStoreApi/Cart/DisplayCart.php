<?php

include_once '../config/database.php';
include_once './Cart.php';

$database = new Database();
$db = $database->getConnection();
 
$Cart = new Cart($db);

$id = $_GET['id'];
$stmt = $Cart->DisplayCart($id);
$num = $stmt->rowCount();

if($num>0){
 
    $shop_arr=array();
    $shop_arr["records"]=array();
 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
        extract($row);

        
        $query = "SELECT * FROM tblproductimage WHERE ProductID=:id LIMIT 1";
        $stmt1 = $db->prepare($query);
        $stmt1->bindparam(":id",$ProdID);
        $stmt1->execute();
        //echo $ProductId;

        $image_arr=array();

        $ImageData = $stmt1->fetch(PDO::FETCH_ASSOC);

        $query = "SELECT * FROM tbldiscount WHERE ProdID=:id AND IsActive=1";
        $stmt1 = $db->prepare($query);
        $stmt1->bindparam(":id",$ProdID);
        $stmt1->execute();
        //echo $ProductId;

        $num = $stmt1->rowcount();
        if($num>0){
            $DiscountData = $stmt1->fetch(PDO::FETCH_ASSOC);
            if(isset($DiscountData['Flat'])){
                $Price = $Price - $DiscountData['Flat'];
            }
               
            if(isset($DiscountData['Percentage'])){
                $Price = $Price - ($Price * $DiscountData['Percentage'])/100;
            }
                
        }
       
        $shop_item=array(
          "CartID" => $CartID,
          "ProductID" => $ProdID,
          "ProductName" => $ProductName,
          "Qty" => $Qty,
          "Price" => $Price,
          "AddedOn" => $AddedOn,
          "Image" => $ImageData['Image'],
          "Stock" => $CurrentStock
        );
 
        array_push($shop_arr["records"], $shop_item);
    }
 
    echo json_encode($shop_arr);
}
 
else{
    echo '{"key":"false"}';
}

?>