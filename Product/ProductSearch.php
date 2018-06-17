<?php

include_once '../config/database.php';
include_once './Product.php';

$database = new Database();
$db = $database->getConnection();

$Product = new Product($db);

//$data = json_decode(file_get_contents("php://input"));

$Search = $_GET['search'];
//$Search = $data->search;

$stmt = $Product->ProductSearch($Search);
$num = $stmt->rowCount();
 //echo $num;
// check if more than 0 record found
if($num>0){

    $shop_arr=array();
    $shop_arr["records"]=array();
 
 
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $query = "SELECT * FROM tblproductimage WHERE ProductID=:id LIMIT 1";
        $stmt1 = $db->prepare($query);
        $stmt1->bindparam(":id",$ProductId);
        $stmt1->execute();
        //echo $ProductId;

       

        $ImageData = $stmt1->fetch(PDO::FETCH_ASSOC);

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
        
        

        

        if($IsActive == 1)
            $IsActive = "Yes";
        else
            $IsActive = "No";
        $shop_item=array(
            "ProductID" => $ProductId,
            "CategoryID" => $CategoryId,
            "ProductName" => $ProductName,
            "ProductDesc" => $ProductDesc,
            "CategoryName" => $CategoryName,
            "ImageAlt" => $ImageAlt,
            "Price" => $Price,
            "Unit" => $Unit,
            "MinStock" => $MinStock,
            "CurrentStock" => $CurrentStock,
            "IsActive" => $IsActive,
            "IsApproved" => $IsApproved,
            "LastStockUpdatedOn" => $LastStockUpdatedOn,
            "CreatedOn" => $CreatedOn,
            "LastUpdatedOn" => $LastUpdatedOn,
            "image" => $ImageData['Image'],
            "Discount" => $discount_arr,
            "FinalPrice" => $finalPrice
           

        );

        array_push($shop_arr["records"], $shop_item);

    }
        
       
    
 
    echo json_encode($shop_arr);
}
 
else{
    echo '{"key":"false"}';
}

?>