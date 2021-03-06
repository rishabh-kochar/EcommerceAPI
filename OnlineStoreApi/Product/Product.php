<?php

include_once '../config/database.php';
require_once '../Notification/Notification.php';


class Product {
 
    // database connection and table name
    private $conn;
    private $table_name = "tblproduct";
    private $target_dir = "../Assets/ProductImages/";
 
    // object properties
    public $PoductID;
    public $CategoryID;
    public $ProductName;
    public $ProductDesc;
    public $Price;
    public $LogoAlt;
    public $Unit;
    public $MinStock;
    public $CurrentStock;
    public $IsActive;
    public $IsApproved;
    public $LastStockUpdatedOn;
    public $CreatedOn;
    public $LastUpdatedOn;
    public $image;
    public $ShopID;

    //Properties
    public $CategoryPropertyID;
    public $PropertyName;
    public $PropertyDesc;
    public $IsFilter;
    public $ColumnOrder;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function AddProduct(){

        $id = $this->ProductID;
        if($id == "new"){
            $query = "INSERT INTO tblproduct(CategoryID, ProductName, ProductDesc, Price,
                        Unit, MinStock, IsActive, IsApproved, CreatedOn, ImageAlt, CurrentStock, ShopID) VALUES 
                        (:CategoryID,:ProductName,:ProductDesc,:Price,
                        :Unit,:MinStock,:IsActive,:IsApproved,:CreatedOn,:LogoAlt,:CurrentStock,:ShopID)";
        }else{

            $query = "UPDATE tblproduct SET CategoryID=:CategoryID, ProductName=:ProductName, ProductDesc=:ProductDesc,
                       Price=:Price, Unit=:Unit, MinStock=:MinStock, LastUpdatedOn=:LastUpdatedOn, ImageAlt=:LogoAlt WHERE
                       ProductID=:ProductID";
        }
        
        $stmt = $this->conn->prepare($query);
        //echo $id;
        if($id == "new"){
            $stmt->bindParam(":CreatedOn", $this->CreatedOn);
            $stmt->bindParam(":IsActive", $this->IsActive);
            $stmt->bindparam(":IsApproved", $this->IsApproved);
            $stmt->bindParam(":CurrentStock", $stock);
            $stmt->bindParam(":ShopID", $this->ShopID);
        }else{
            $stmt->bindParam(":ProductID", $id);
            $stmt->bindParam(":LastUpdatedOn", $this->LastUpdatedOn);
        }
        $stock = 0;
        $stmt->bindParam(":CategoryID", $this->CategoryID);
        $stmt->bindParam(":ProductName", $this->ProductName);
        $stmt->bindParam(":ProductDesc", $this->ProductDesc);
        $stmt->bindParam(":Price", $this->Price);
        $stmt->bindParam(":Unit", $this->Unit);
        $stmt->bindParam(":MinStock", $this->MinStock);
        $stmt->bindParam(":LogoAlt", $this->LogoAlt);
        
        
        
         
        if($stmt->execute()){
            if($id == "new"){
                $id = $this->conn->lastInsertId();
                $Notification = new Notification($this->conn);
                $Notification->URL = "/suproduct";
                $Notification->Type = "0";
                $Notification->Image = "fa-gift";
                $Notification->IsRead = "0";
                $Notification->NotificationText = $this->ProductName . " Product Added.";
                $Notification->CreatedOn = date('Y-m-d H:i:s');
                $Notification->AddNotification();
               
            }
               
            return $id;
        }
     
        return null;


    }

    function ProductData($id){
        $query = "SELECT *,p.IsActive prodActive FROM tblproduct p
        LEFT JOIN tblcategory as c on p.CategoryID = c.CategoryID
        LEFT JOIN tblshops as s on p.ShopID = s.ShopID
        WHERE p.IsApproved=1 and s.ShopID=:shopid 
        ORDER BY ProductID DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":shopid",$id);
        $stmt->execute();
        return $stmt;
    }

    function SingleProductData($id){
        $query = "SELECT * FROM tblproduct p
                    LEFT JOIN tblcategory as c on p.CategoryID = c.CategoryID
                    LEFT JOIN tblshops as s on p.ShopID = s.ShopID
                    LEFT JOIN tblcategoryproperties as cp on c.CategoryID = c.CategoryID
                    LEFT JOIN tblcategorypropertiesvalues as cpv on cp.CategoryPropertyID = cpv.CategoryPropertyID
                    WHERE p.IsApproved=1 and p.ProductID=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":id",$id);
        $stmt->execute();
        return $stmt;
    }

    function ProductImageUpdate($id){

        $query = "UPDATE tblproductimage SET Image=:image WHERE id=:id";
     
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->AdminImage=htmlspecialchars(strip_tags($this->AdminImage));
        
        // bind values
        $stmt->bindParam(":image", $this->Image);
        $stmt->bindParam(":id", $this->PoductID);
        
     
        // execute query
        if($stmt->execute()){
            return true;
        }
     
        return false;
    }

    function AddStock($stock){
        //echo $this->ProductID;
        $res = $this->SingleProductData($this->ProductID);
        $row = $res->fetch(PDO::FETCH_ASSOC);
        
        //echo $row['MinStock'] . "<" . ($row['CurrentStock'] + $stock); 
        if($row['MinStock'] > ($row['CurrentStock'] + $stock))
            return false;
        //echo $row['ProductName'];
        //echo $row['CurrentStock'];
       
        $query = "UPDATE tblproduct SET CurrentStock=:stock, LastStockUpdatedOn=:LastStockUpdatedOn WHERE
                    ProductId=:ProductId";
        $stmt = $this->conn->prepare($query);

        $stock = $row['CurrentStock'] + $stock;
        
        $stmt->bindParam(":stock", $stock);
        $stmt->bindParam(":LastStockUpdatedOn", $this->LastStockUpdatedOn);
        $stmt->bindParam(":ProductId", $this->ProductID);

        if($stmt->execute())
            return true;
        else
            return false;
                    
    }

    function DeleteImage($id){

        $query = "SELECT * FROM tblproductimage WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        if($stmt->rowcount()>0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(file_exists($this->target_dir . $row['Image'])){
                //fclose($this->target_dir);
                unlink($this->target_dir . $row['Image']);
            }
                
        }
       

        $query = "DELETE FROM tblproductimage WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        if($stmt->execute())
            return true;
        else
            return false;
    }

    function AllProduct(){
        $query = "SELECT *,p.IsApproved ProductApproved FROM " . $this->table_name . " p
                    LEFT JOIN tblcategory as c on p.CategoryID = c.CategoryID
                    LEFT JOIN tblshops as s on p.ShopID = s.ShopID
                    ORDER BY ProductId";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function DisableProduct($id,$status){
        $query = "UPDATE " . $this->table_name . " SET IsApproved=:status WHERE ProductId=:ProductId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":ProductId", $id);
        $stmt->bindParam(":status", $status);
        if($stmt->execute()){
            return true;
        }
        return false;   
    }

    function ActiveStatusProduct($id,$status){
        $query = "UPDATE " . $this->table_name . " SET IsActive=:status WHERE ProductId=:ProductId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":ProductId", $id);
        $stmt->bindParam(":status", $status);
        if($stmt->execute()){
            return true;
        }
        return false;   
    }

    function DiscountProduct($id){
        $query = "SELECT * From tblproduct P, tbldiscount d WHERE P.ProductId = d.ProdID";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $num = $stmt->rowcount();
        if($num>0){
            $query = "SELECT * FROM tblproduct p WHERE p.ProductId NOT IN( SELECT P.ProductId From tblproduct P, tbldiscount d 
                        WHERE P.ProductId = d.ProdID AND p.ShopID=:id AND p.IsApproved=1) AND p.ShopID=:id AND p.IsApproved=1;";
            $stmt = $this->conn->prepare($query);
            $stmt->bindparam(":id",$id);
            $stmt->execute();
        }else{
            $query = "SELECT * FROM tblproduct p WHERE p.ShopID=:id AND p.IsApproved=1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindparam(":id",$id);
            $stmt->execute();
        }
        return $stmt;
    }

    function GetCategoryProduct($id){
        $query = "SELECT * FROM tblproduct p
                    LEFT JOIN tblcategory c on p.CategoryID = c.CategoryID
                     WHERE p.CategoryID=:id AND p.IsApproved=1 AND p.IsActive=1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":id",$id);
        $stmt->execute();
        return $stmt;
    }

    function CategoryFilter($id){
        $query = "SELECT * FROM tblcategoryproperties c
                    WHERE c.CategoryID = :id AND c.IsFilter = 1 ORDER BY c.ColumnOrder;";
         $stmt = $this->conn->prepare($query);
         $stmt->bindparam(":id",$id);
         $stmt->execute();
         return $stmt;
    } 

    function FilterData($id,$Search,$min,$max,$Properties){
        $stmt = $this->GetCategoryProduct($id);
        $size = sizeof($Properties);
        $num = $stmt->rowCount();
    
        if($num>0){
            $shop_arr=array();
            $shop_arr["records"]=array();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);
           
                $str = "";
                $flag = 1;

                //echo $ProductName . " " . $Search . "\n";
                if($Search != ""){
                    if(stripos($ProductName, $Search) === false){
                        $flag = 0;
                        //echo "false \n";
                    }
                        
                }

                if(!($Price >= $min && $Price <= $max))
                    $flag=0;

                if($flag == 1){
                    for($i=0;$i<$size;$i++){
                   
                        $key = $Properties[$i]->key;
                        $value = $Properties[$i]->value;
                        //echo $key . "\n";
                        if($value != ""){
                            $str = "(CategoryPropertyID = " . $key . " AND Value LIKE '" . $value . "')";
                            $query = "SELECT * FROM tblcategorypropertiesvalues 
                                        WHERE ProductID=".$ProductId." AND " . $str;
                            $stmt1 = $this->conn->prepare($query);
                            //$stmt1->bindparam(":id",$ProductID);
                            //echo $query . "\n";
                            $stmt1->execute();
                            if($stmt1->rowcount()==0)
                                $flag=0;
                        }
                        if($flag==0)
                            break;
                            
                    }
                }
               
              
                if($flag==1){
                    $query = "SELECT * FROM tblcategoryproperties WHERE CategoryID=:id ORDER BY ColumnOrder";
                    $stmt1 = $this->conn->prepare($query);
                    $stmt1->bindparam(":id",$CategoryId);
                    $stmt1->execute();
                
                    $num = $stmt1->rowCount();
                    $flag = 0;
                    if($num>0){
                        $properties=array();    
                        while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)){
                            extract($row);

                            if($flag == 0){
                                $query = "SELECT * FROM tblcategorypropertiesvalues WHERE ProductID=:pid AND CategoryPropertyID=:cid";
                                $stmtvalue = $this->conn->prepare($query);
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

                    //Product Image
                    $query = "SELECT * FROM tblproductimage WHERE ProductID=:id";
                    $stmt1 = $this->conn->prepare($query);
                    $stmt1->bindparam(":id",$ProductId);
                    $stmt1->execute();
            
                    $image_arr=array();
            
                    while($ImageData = $stmt1->fetch(PDO::FETCH_ASSOC)){
                        $image_item['id']=$ImageData['id'];
                        $image_item['image']=$ImageData['Image'];
                        array_push($image_arr, $image_item);
                    }

                    //Discount

                    $query = "SELECT * FROM tbldiscount WHERE ProdID=:id AND IsActive=1";
                    $stmt1 = $this->conn->prepare($query);
                    $stmt1->bindparam(":id",$ProductId);
                    $stmt1->execute();
                                
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
                        "image" => $image_arr,
                        "Properties" => $properties,
                        "Discount" => $discount_arr,
                        "FinalPrice" => $finalPrice
                    );

                    array_push($shop_arr["records"], $shop_item);
                }

            }
            return json_encode($shop_arr);
        }else{
            return '{"key":"false"}';
        }
    }

    function ProductSearch($Search){
        $query = "SELECT * FROM tblproduct p
                    LEFT JOIN tblcategory c on p.CategoryID = c.CategoryID
                     WHERE p.ProductName LIKE :name AND p.IsApproved=1 AND p.IsActive=1";
        $stmt = $this->conn->prepare($query);
        $Search = "%".$Search."%";
        $stmt->bindparam(":name",$Search);
        $stmt->execute();
        return $stmt;
    }

    function MostSelledProduct(){
        $query = "SELECT ProductID,count(*) cnt FROM tblorderdetails
                    Group BY ProductID
                    Order By cnt desc
                    Limit 12";
         $stmt = $this->conn->prepare($query);
         $stmt->execute();
         if($stmt->rowcount()>=4){
            $str = "";
            while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                $str .= $row['ProductID'] . ",";
            }
            $str .= "0";
            //echo $query;
            $query = "SELECT * FROM tblproduct p
                        LEFT JOIN tblcategory c on p.CategoryID = c.CategoryID
                        WHERE p.ProductID IN (" . $str ." ) AND p.IsApproved=1 AND p.IsActive=1";
                        $stmt = $this->conn->prepare($query);
                        //echo $query;
                        $stmt->execute();
                    return $stmt;
         }else{
            $query = "SELECT * FROM tblproduct p
                        LEFT JOIN tblcategory c on p.CategoryID = c.CategoryID
                        WHERE  p.IsApproved=1 AND p.IsActive=1
                        ORDER BY p.ProductID DESC
                        LIMIT 12";
            $stmt = $this->conn->prepare($query);
            $stmt->bindparam(":ids",$id);
            $stmt->execute();
            return $stmt;
         }

    }

    function PercentageDiscountProduct(){


        $query = "(SELECT Count(*) cnt FROM `tbldiscount` d 
                    WHERE d.Flat IS NOT NULL and d.IsActive=1)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $Flat = $row['cnt'];

        $query = "(SELECT Count(*) cnt FROM `tbldiscount` d 
            WHERE d.Percentage IS NOT NULL and d.IsActive=1)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $Percentage = $row['cnt'];
                    
        

        if($Flat>=2 && $Percentage>=2){
            $query = "(SELECT * FROM tblproduct p
                        LEFT JOIN tblcategory c on p.CategoryID = c.CategoryID
                        WHERE p.ProductID IN
                            (SELECT ProdID FROM `tbldiscount` d 
                            LEFT JOIN tblproduct p on p.ProductID = d.ProdID
                            WHERE d.Flat IS NOT NULL AND d.IsActive=1 AND p.IsApproved=1 AND p.IsActive=1
                            Order BY d.Percentage DESC 
                            ) AND p.IsApproved=1 AND p.IsActive=1
                        LIMIT 6)
                    
                    UNION 
                    
                    (SELECT * FROM tblproduct p
                        LEFT JOIN tblcategory c on p.CategoryID = c.CategoryID
                        WHERE p.ProductID IN
                            (SELECT ProdID FROM `tbldiscount` d 
                            LEFT JOIN tblproduct p on p.ProductID = d.ProdID
                            WHERE d.Percentage IS NOT NULL AND d.IsActive=1 AND p.IsApproved=1 AND p.IsActive=1
                            Order BY d.Flat DESC 
                            ) AND p.IsApproved=1 AND p.IsActive=1
                            LIMIT 6)";
         $stmt = $this->conn->prepare($query);
         $stmt->execute();
         return $stmt;

        }else{
            $query = "SELECT * FROM tblproduct p
            LEFT JOIN tblcategory c on p.CategoryID = c.CategoryID
            WHERE  p.IsApproved=1 AND p.IsActive=1
            ORDER BY p.ProductID 
            LIMIT 12";
            $stmt = $this->conn->prepare($query);
            $stmt->bindparam(":ids",$id);
            $stmt->execute();
            return $stmt;
        }
    }

    

}
?>