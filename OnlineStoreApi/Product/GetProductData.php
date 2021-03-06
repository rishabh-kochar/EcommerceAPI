<?php

include_once '../config/database.php';
include_once './Product.php';

$database = new Database();
$db = $database->getConnection();

$Product = new Product($db);

//$data = json_decode(file_get_contents("php://input"));

$id = $_GET['id'];
//$id = 2;

$stmt = $Product->ProductData($id);
$num = $stmt->rowCount();


if($num>0){
 
    $shop_arr=array();
    $shop_arr["records"]=array();
 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
        extract($row);

        $query = "SELECT * FROM tblproductimage WHERE ProductID=:id";
        $stmt1 = $db->prepare($query);
        $stmt1->bindparam(":id",$ProductId);
        $stmt1->execute();
        //echo $ProductId;
        $i=0;
        $image_arr=array();

        while($ImageData = $stmt1->fetch(PDO::FETCH_ASSOC)){
            $image_item['id']=$ImageData['id'];
            $image_item['image']=$ImageData['Image'];
            array_push($image_arr, $image_item);
            $i++;
        }

        $query = "SELECT * FROM tbldiscount WHERE ProdID=:id AND IsActive=1";
        $stmt1 = $db->prepare($query);
        $stmt1->bindparam(":id",$ProductId);
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
            "ProductID" => $ProductId,
            "CategoryID" => $CategoryId,
            "CategoryName" => $CategoryName,
            "ProductName" => $ProductName,
            "ProductDesc" => $ProductDesc,
            "ImageAlt" => $ImageAlt,
            "Price" => $Price,
            "Unit" => $Unit,
            "MinStock" => $MinStock,
            "CurrentStock" => $CurrentStock,
            "IsActive" => $prodActive,
            "IsApproved" => $IsApproved,
            "LastStockUpdatedOn" => $LastStockUpdatedOn,
            "CreatedOn" => $CreatedOn,
            "LastUpdatedOn" => $LastUpdatedOn,
            "Image" => $image_arr

        );
 
        array_push($shop_arr["records"], $shop_item);
    }
 
    echo json_encode($shop_arr);
}
 
else{
    echo '{"key":"false"}';
}

?>