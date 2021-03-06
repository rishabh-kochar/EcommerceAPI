<?php

include_once '../config/database.php';
include_once '../phpmailer/Mailer/Mail.php';



class Discount {
 
    // database connection and table name
    private $conn;
    private $table_name = "tbldiscount";
 
    // object properties
    public $ProdID;
    public $Flat;
    public $Percentage;
    public $IsActive;
    public $CreatedOn;
    public $UpdatedOn;

    public function __construct($db){
        $this->conn = $db;
    }

    function AddDiscount($id){

        if($id == "new"){
            $query = "INSERT INTO tbldiscount(ProdID,Flat,Percentage,IsActive,CreatedOn)
                    VALUES(:ProdID,:Flat,:Percentage,:IsActive,:CreatedOn)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindparam(":ProdID",$this->ProdID);
            $stmt->bindparam(":Flat",$this->Flat);
            $stmt->bindparam(":Percentage",$this->Percentage);
            $stmt->bindparam(":IsActive",$this->IsActive);
            $stmt->bindparam(":CreatedOn",$this->CreatedOn);
            if($stmt->execute())
                return true;
            return false;
        }else{
            $query = "UPDATE tbldiscount SET Flat=:Flat, Percentage=:Percentage, IsActive=:IsActive,
                        UpdatedOn=:UpdatedOn WHERE ProdID=:ProdID";
            $stmt = $this->conn->prepare($query);
            $stmt->bindparam(":ProdID",$this->ProdID);
            $stmt->bindparam(":Flat",$this->Flat);
            $stmt->bindparam(":Percentage",$this->Percentage);
            $stmt->bindparam(":IsActive",$this->IsActive);
            $stmt->bindparam(":UpdatedOn",$this->UpdatedOn);
            if($stmt->execute())
                return true;
            return false;
        }

    }

    function GetAllShopDiscount($ShopID){
        $query = "SELECT *,d.IsActive DiscountStatus FROM tbldiscount d
                    LEFT JOIN tblProduct as p on d.ProdID = p.ProductID
                    WHERE p.ShopID =:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":id",$ShopID);
        $stmt->execute();
        return $stmt;
    }

    function getSingleProductDiscount($ProductID){
        $query = "SELECT *,d.IsActive DiscountStatus FROM tbldiscount d
                    LEFT JOIN tblProduct as p on d.ProdID = p.ProductID
                    WHERE d.ProdID =:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":id",$ProductID);
        $stmt->execute();
        return $stmt;
    }

    function SetDiscountStatus($id,$status){
        $query = "UPDATE tbldiscount SET IsActive=:status WHERE ProdID=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":status",$status);
        $stmt->bindparam(":id",$id);
        if($stmt->execute())
            return true;
        return false;
    }

}




?>