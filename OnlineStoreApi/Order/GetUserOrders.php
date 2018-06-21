<?php

include_once '../config/database.php';
include_once './Orders.php';

$database = new Database();
$db = $database->getConnection();
 
$Order = new Order($db);
$id = $_GET['id'];

$stmt = $Order->GetUserOrders($id);
$num = $stmt->rowCount();
 //echo $num;
// check if more than 0 record found
if($num>0){
 
    $shop_arr=array();
    $shop_arr["records"]=array();
 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
        extract($row);

        $query = "SELECT * FROM tbldiscount WHERE ProdID=:id AND IsActive=1";
        $stmt1 = $db->prepare($query);
        $stmt1->bindparam(":id",$ProductId);
        $stmt1->execute();
        //echo $ProductId;

        $num = $stmt1->rowcount();
        if($num>0){
            $DiscountData = $stmt1->fetch(PDO::FETCH_ASSOC);
            if(isset($DiscountData['Flat'])){
                $discount_arr = array(
                    "Type" => "1",
                    "Flat" =>  $DiscountData['Flat']
                );
                $finalPrice = $Price - $DiscountData['Flat'];
            }
               
            if(isset($DiscountData['Percentage'])){
                $discount_arr = array(
                    "Type" => "2",
                    "Percentage" =>  $DiscountData['Percentage']
                );
                $finalPrice = $Price - ($Price * $DiscountData['Percentage'])/100;
            }
                
        }else{
            $discount_arr = null;
            $finalPrice = 0;
        }

        $query = "SELECT * FROM tblproductimage WHERE ProductID=:id LIMIT 1";
        $stmt1 = $db->prepare($query);
        $stmt1->bindparam(":id",$ProductID);
        $stmt1->execute();
        //echo $ProductId;

        $image_arr=array();

        $ImageData = $stmt1->fetch(PDO::FETCH_ASSOC);

        if($Status == 'Delievered'){
            $DelieveryDate = $OrderUpdatedOn;
        }else{
            $DelieveryDate = '';   
        }


 
        $shop_item=array(
            "OrderID" => $OrderID,
          "ProductName" => $ProductName,
          "Qty" => $Qty,
          "Price" => $Price,
          "FinalPrice" => $PurchasedPrice,
          "OrderDetailID" => $OrderDetailID,
          "AddressID" => $AddressID,
            "AddressName" => $Name,
            "AddressPhoneNo" => $PhoneNo,
            "Pincode" => $Pincode,
            "Locality" => $Locality,
            "Address" => $Address,
            "City" => $City,
            "State" => $State,
            "LandMark" => $Landmark,
            "Country" => $Country,
            "AddressType" => $AddressType,
            "Discount" => $discount_arr,
            "Image" => $ImageData['Image'],
            "SellerName" => $ShopName,
            "OrderDate" => $OrderedOn,
            "DelieveryDate" => $DelieveryDate,
            "Status" => $Status

        );
 
        array_push($shop_arr["records"], $shop_item);
    }
 
    echo json_encode($shop_arr);
}
 
else{
    echo '{"key":"false"}';
}

?>