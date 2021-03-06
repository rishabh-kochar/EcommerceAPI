<?php

include_once '../config/database.php';
include_once './Product.php';

$database = new Database();
$db = $database->getConnection();

$Product = new Product($db);

//$data = json_decode(file_get_contents("php://input"));

$id = $_GET['id'];
//$id = 1;

$stmt = $Product->GetCategoryProduct($id);
$num = $stmt->rowCount();
 //echo $num;
// check if more than 0 record found
if($num>0){

    $shop_arr=array();
    $shop_arr["records"]=array();
 
 
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $query = "SELECT * FROM tblproductimage WHERE ProductID=:id";
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
        
        

        //Properties
        $query = "SELECT * FROM tblcategoryproperties WHERE CategoryID=:id ORDER BY ColumnOrder";
        $stmt1 = $db->prepare($query);
        $stmt1->bindparam(":id",$CategoryId);
        $stmt1->execute();
        
        $num = $stmt1->rowCount();
        //echo $num;
        // check if more than 0 record found
        $flag = 0;
        if($num>0){
                $properties=array();
                
                while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)){
                   
                    extract($row);

                    if($flag == 0){
                        $query = "SELECT * FROM tblcategorypropertiesvalues WHERE ProductID=:pid AND CategoryPropertyID=:cid";
                        $stmtvalue = $db->prepare($query);
                        $stmtvalue->bindparam(":pid",$ProductId);
                        $stmtvalue->bindparam(":cid",$CategoryPropertyID);
                        $stmtvalue->execute();

                        if($stmtvalue->rowcount()>0){
                            $rowvalue = $stmtvalue->fetch(PDO::FETCH_ASSOC);
                            $value = $rowvalue['Value'];
                        }else{
                            $flag=1;
                            $value = "";
                        }
                    }
                    
                    $properties_item["IsFilter"]=$IsFilter;
                    $properties_item["PropertyName"]=$PropertyName;
                    $properties_item["Value"]=$value;
                
            
                    array_push($properties, $properties_item);
                }
            }else{
                $properties = null;
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
            "image" => $image_arr,
            "Properties" => $properties,
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