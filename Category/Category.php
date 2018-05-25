<?php

include_once '../config/database.php';


class Category {
 
    // database connection and table name
    private $conn;
    private $table_name = "tblcategory";
 
    // object properties
    public $CategoryID;
    public $CategoryName;
    public $CategoryDesc;
    public $CategoryImage;
    public $CategoryImageAlt;
    public $ShopID;
    public $IsActive;
    public $LastUpdatedOn;
    public $CreatedOn;

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

    function AddCategory(){

        $id = $this->CategoryID;
        if($id == "new"){
            $query = "INSERT INTO " . $this->table_name . " (CategoryName,CategoryDesc,ShopID,CreatedOn,IsActive,
                        CategoryImage,CategoryImageAlt)
                        values (:CategoryName,:CategoryDesc,:ShopID,:CreatedOn,:IsActive,
                        :CategoryImage,:CategoryImageAlt);";
        }else{

            $query = "UPDATE " . $this->table_name . " SET CategoryName=:CategoryName,CategoryDesc=:CategoryDesc,
            ShopID=:ShopID, LastUpdatedOn=:LastUpdatedOn, CategoryImage=:CategoryImage, CategoryImageAlt=:CategoryImageAlt
             WHERE CategoryID=:CategoryID";
        }
        
        $stmt = $this->conn->prepare($query);
        //echo $query;
        if($id == "new"){
            $stmt->bindParam(":CreatedOn", $this->CreatedOn);
            $stmt->bindParam(":IsActive", $this->IsActive);
        }else{
            $stmt->bindParam(":CategoryID", $id);
            $stmt->bindParam(":LastUpdatedOn", $this->LastUpdatedOn);
        }

        $stmt->bindParam(":ShopID", $this->ShopID);
        $stmt->bindParam(":CategoryName", $this->CategoryName);
        $stmt->bindParam(":CategoryDesc", $this->CategoryDesc);
        $stmt->bindParam(":CategoryImageAlt", $this->CategoryImageAlt);
        $stmt->bindParam(":CategoryImage", $this->CategoryImage);
        
        
         
        if($stmt->execute()){
            if($id == "new")
                $id = $this->conn->lastInsertId();
            return '{"key":"true","id":'.$id.'}';
        }
     
        return '{"key":"false"}';
    }

    function DeleteCategory($id){
        $query = "UPDATE " . $this->table_name . " SET IsActive=0 WHERE CategoryID=:CategoryID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":CategoryID", $id);
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    function CategoryData($id){
        $query = "SELECT * FROM " . $this->table_name . " WHERE IsActive=1 AND ShopID=:id ORDER BY CategoryID Desc;";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":id",$id);
        $stmt->execute();
        return $stmt;

    }

    function SingleCategoryData($Categoryid){
        $query = "SELECT * FROM " . $this->table_name . " WHERE IsActive=1 AND CategoryId=:id ORDER BY CategoryID Desc;";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":id",$id);
        $stmt->execute();
        return $stmt;

    }

    function AddProperties($Categoryid,$PropertiedData){

        $flag = 1;
        $size = sizeof($PropertiedData);
        //echo $size;

        for($i=0;$i<$size;$i++){

            $id = ($PropertiedData[$i])->{"ID"};
            if($id == "new"){
                $query = "INSERT INTO tblcategoryproperties (CategoryID,PropertyName,PropertyDesc,Isfilter, ColumnOrder) 
                values(:CategoryID,:PropertyName,:PropertyDesc,:Isfilter,:ColumnOrder);";

                $stmt = $this->conn->prepare($query);
                $this->PropertyName = ($PropertiedData[$i])->{"PropertyName"};
                $this->PropertyDesc = ($PropertiedData[$i])->{"PropertyDesc"};
                $this->IsFilter = ($PropertiedData[$i])->{"IsFilterable"};
                $this->ColumnOrder = ($PropertiedData[$i])->{"ColumnOrder"};
                $stmt->bindparam(":CategoryID",$Categoryid);
                $stmt->bindparam(":PropertyName",$this->PropertyName);
                $stmt->bindparam(":PropertyDesc", $this->PropertyDesc);
                $stmt->bindparam(":Isfilter",$this->IsFilter);
                $stmt->bindparam(":ColumnOrder",$this->ColumnOrder);
                echo $this->PropertyName;
                if(!$stmt->execute())
                    $flag = 0;
            }else{
                $query = "UPDATE tblcategoryproperties SET CategoryId=:CategoryId, PropertyName=:PropertyName, PropertyDesc=:PropertyDesc,
                Isfilter=:Isfilter,ColumnOrder=:ColumnOrder WHERE CategoryPropertyID=:id";
                $stmt = $this->conn->prepare($query);
                
                $this->PropertyName = ($PropertiedData[$i])->{"PropertyName"};
                $this->PropertyDesc = ($PropertiedData[$i])->{"PropertyDesc"};
                $this->IsFilter = ($PropertiedData[$i])->{"IsFilterable"};
                $this->ColumnOrder = ($PropertiedData[$i])->{"ColumnOrder"};
                $stmt->bindparam(":CategoryID",$Categoryid);
                $stmt->bindparam(":id",$id);
                $stmt->bindparam(":PropertyName",$this->PropertyName);
                $stmt->bindparam(":PropertyDesc", $this->PropertyDesc);
                $stmt->bindparam(":Isfilter",$this->IsFilter);
                $stmt->bindparam(":ColumnOrder",$this->ColumnOrder);
                if(!$stmt->execute())
                    $flag = 0;
            }
            //echo $query . "\n";
            
        }

        if($flag == 1)
            return '{"key":"true"}';
        else
            return '{"key":"false"}';
        
    }

    function CategoryPropertyData($id){

        $query = "SELECT * FROM tblcategoryproperties cp
                LEFT JOIN tblCategory as c on cp.CategoryID = c.CategoryID  
                WHERE IsActive=1 AND cp.CategoryID=:id 
                ORDER BY ColumnOrder;";
        //echo $query;
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":id",$id);
        $stmt->execute();
        return $stmt;

    }
    
}




?>