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

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function AddCategory(){

        $id = $this->CategoryID;
        if($id == "new"){
            $query = "INSERT INTO " . $this->table_name . " (CategoryName,CategoryDesc,ShopID,CreatedOn,IsActive,
                        CategoryImage,CategoryImageAlt)
                        values (:CategoryName,:CategoryDesc,:ShopID,:CreatedOn,:IsActive,:CategoryImage,:CategoryImageAlt);";
        }else{

            

            $query = "UPDATE " . $this->table_name . " SET CategoryName=:CategoryName,CategoryDesc=:CategoryDesc,
            ShopID=:ShopID, LastUpdatedOn=:LastUpdatedOn, CategoryImage=:CategoryImage, CategoryImageAlt=:CategoryImageAlt
             WHERE CategoryID=:CategoryID";
        }
        
        $stmt = $this->conn->prepare($query);

        if($id == "new"){
            $stmt->bindParam(":CreatedOn", $this->CreatedOn);
            $stmt->bindParam(":IsActive", $this->IsActive);
        }else{
            $stmt->bindParam(":CategoryID", $id);
            $stmt->bindParam(":LastUpdatedOn", $this->LastUpdatedOn);
        }

        $stmt->bindParam(":CategoryName", $this->CategoryName);
        $stmt->bindParam(":CategoryDesc", $this->CategoryDesc);
        $stmt->bindParam(":CategoryImageAlt", $this->CategoryImageAlt);
        $stmt->bindParam(":CategoryImage", $this->CategoryImage);
        
        if($stmt->execute()){
            return true;
        }
     
        return false;
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
    
}




?>