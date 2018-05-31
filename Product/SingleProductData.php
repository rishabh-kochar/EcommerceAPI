<?php

include_once '../config/database.php';
include_once './Product.php';

$database = new Database();
$db = $database->getConnection();

$Product = new Product($db);

//$data = json_decode(file_get_contents("php://input"));

$id = $_GET['id'];
//$id = 1;

$stmt = $Product->SingleProductData($id);
$num = $stmt->rowCount();
 //echo $num;
// check if more than 0 record found
if($num>0){
 
 
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        extract($row);
        $query = "SELECT * FROM tblproductImage WHERE ProductID=:id";
        $stmt1 = $db->prepare($query);
        $stmt1->bindparam(":id",$ProductId);
        $stmt1->execute();
        //echo $ProductId;

        $image_arr=array();

        while($ImageData = $stmt1->fetch(PDO::FETCH_ASSOC)){
            $image_item['id']=$ImageData['id'];
            $image_item['image']=$ImageData['Image'];
            array_push($image_arr, $image_item);
        }

        if($IsActive == 1)
            $IsActive = "Yes";
        else
            $IsActive = "No";
        $shop_arr=array(
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
            "image" => $image_arr

        );

    
 
    echo json_encode($shop_arr);
}
 
else{
    echo '{"key":"false"}';
}

?>