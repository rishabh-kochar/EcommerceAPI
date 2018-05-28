<?php

include_once '../config/database.php';


class Category {
 
    // database connection and table name
    private $conn;
    private $table_name = "tblproduct";
 
    // object properties
    public $PoductID;
    public $CategoryID;
    public $ProductName;
    public $ProductDesc;
    public $Price;
    public $ProductImage1;
    public $ProductImage2;
    public $ProductImage3;
    public $ProductImage4;
    public $Unit;
    public $MinStock;
    public $CurrentStock;
    public $IsActive;
    public $IsApproved;
    public $LastStockUpdatedOn;
    public $CreatedOn;
    public $LastUpdatedOn;

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
            $query = "INSERT INTO tblproduct(CategoryID, ProductName, ProductDesc, Price, ProductImage1, 
                        ProductImage2, ProductImage3, ProductImage4, Unit, MinStock, 
                         IsActive, IsApproved, CreatedOn) VALUES 
                        (:CategoryID,:ProductName,:ProductDesc,:Price,:ProductImage1,:ProductImage2,
                        :ProductImage3,:ProductImage4,:Unit,:MinStock,:IsActive,:IsApproved,:CreatedOn)";
        }else{

            $query = ;
        }
        
        $stmt = $this->conn->prepare($query);
        //echo $query;
        if($id == "new"){
            $stmt->bindParam(":CreatedOn", $this->CreatedOn);
            $stmt->bindParam(":IsActive", $this->IsActive);
            $stmt->bindparam(":IsApproved", $this->IsApproved);
        }else{
            $stmt->bindParam(":CategoryID", $id);
            $stmt->bindParam(":LastUpdatedOn", $this->LastUpdatedOn);
        }

        $stmt->bindParam(":CategoryID", $this->CategoryID);
        $stmt->bindParam(":ProductName", $this->ProductName);
        $stmt->bindParam(":ProductDesc", $this->ProductDesc);
        $stmt->bindParam(":Price", $this->Price);
        $stmt->bindParam(":ProductImage1", $this->ProductImage1);
        $stmt->bindParam(":ProductImage2", $this->ProductImage2);
        $stmt->bindParam(":ProductImage3", $this->ProductImage3);
        $stmt->bindParam(":ProductImage4", $this->ProductImage4);
        $stmt->bindParam(":Unit", $this->Unit);
        $stmt->bindParam(":MinStock", $this->MinStock);
        
        
         
        if($stmt->execute()){
            if($id == "new")
                $id = $this->conn->lastInsertId();
            return '{"key":"true","id":"' . $id. '"}';
        }
     
        return '{"key":"false"}';


    }


}




?>