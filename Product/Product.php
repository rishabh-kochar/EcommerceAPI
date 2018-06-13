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


}
?>