<?php

require_once '../Config/Database.php';
include_once './Product.php';

$database = new Database();
$db = $database->getConnection();

$Product = new Product($db);

//$data = json_decode(file_get_contents("php://input"));

$id = $_GET['id'];
//$id = 3;

$stmt = $Product->CategoryFilter($id);
$num = $stmt->rowCount();
 //echo $num;


if($num>0){
 
    $shop_arr=array();
    $shop_arr["records"]=array();

    $query = "SELECT max(Price) maxPrice,min(Price) minPrice FROM tblproduct p 
                Where p.CategoryID = :id AND p.IsApproved=1 AND p.IsActive=1;";
    $stmt1 = $db->prepare($query);
    $stmt1->bindparam(":id",$id);
    $stmt1->execute();
    $pricedata = $stmt1->fetch(PDO::FETCH_ASSOC);
    $shop_arr["Min"] = $pricedata['minPrice'];
    if($pricedata['minPrice'] != $pricedata['maxPrice'])
        $shop_arr["Max"] = $pricedata['maxPrice']+1000;
    else
    $shop_arr["Max"] = $pricedata['maxPrice'];
 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
        extract($row);

        $query = "SELECT DISTINCT(Value) val From tblcategorypropertiesvalues c
                    LEFT JOIN tblproduct p on p.ProductID = c.ProductID
                    WHERE c.CategoryPropertyID = :id AND p.IsApproved=1 AND p.IsActive=1;";
        $stmt1 = $db->prepare($query);
        $stmt1->bindparam(":id",$CategoryPropertyID);
        $stmt1->execute();
        $num = $stmt1->rowCount();
 
        $Values = array();
        
        if($num>0){
            while ($valuesdata = $stmt1->fetch(PDO::FETCH_ASSOC)){
                $value_item = $valuesdata['val'];
                array_push($Values, $value_item);
            }
        }else{
            $Values = [];
        }
         

        $shop_item=array(
            "CategoryPropertyID" => $CategoryPropertyID,
            "PropertyName" => $PropertyName,
            "Values" => $Values

        );
 
        array_push($shop_arr["records"], $shop_item);
    }
 
    echo json_encode($shop_arr);
}
 
else{
    $shop_arr=array();
    $query = "SELECT max(Price) maxPrice,min(Price) minPrice FROM tblproduct p 
                Where p.CategoryID = :id AND p.IsApproved=1 AND p.IsActive=1;;";
    $stmt1 = $db->prepare($query);
    $stmt1->bindparam(":id",$id);
    $stmt1->execute();
    $pricedata = $stmt1->fetch(PDO::FETCH_ASSOC);
    $shop_arr["Min"] = $pricedata['minPrice'];
    if($pricedata['minPrice'] != $pricedata['maxPrice'])
        $shop_arr["Max"] = $pricedata['maxPrice']+1000;
    else
    $shop_arr["Max"] = $pricedata['maxPrice'];
    echo json_encode($shop_arr);
}

?>