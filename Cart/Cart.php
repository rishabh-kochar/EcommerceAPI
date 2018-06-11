<?php

include_once '../config/database.php';
include_once '../phpmailer/Mailer/Mail.php';
require_once '../Notification/Notification.php';


class Cart {
 
    // database connection and table name
    private $conn;
    private $table_name = "tblcart";
 
    // object properties
    public $CartID;
    public $ProducyID;
    public $UserID;
    public $Qty;
    public $AddedOn;

     // constructor with $db as database connection
     public function __construct($db){
        $this->conn = $db;
    }

    function DisplayCart($id){
        $query = "SELECT *,c.ProductID ProdID FROM tblcart c
                    LEFT JOIN tblproduct as p on c.ProductID = p.ProductID
                    WHERE c.UserID=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":id",$id);
        $stmt->execute();
        return $stmt;
    }

    function AddToCart(){

        $query = "SELECT * FROM tblcart WHERE ProductID=:prodid AND UserID=:userid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":prodid",$this->ProductID);
        $stmt->bindparam(":userid",$this->UserID);
        $stmt->execute();
        $num = $stmt->rowcount();
        if($num>0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $query = "UPDATE tblcart SET Qty=Qty+1 WHERE CartID=:id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindparam(":id",$row['CartID']);
            if($stmt->execute())
                return true;
            return false;
        }else{
            $query = "INSERT INTO tblcart(ProductID,UserID,Qty,AddedOn)
                    VALUES(:ProductID,:UserID,:Qty,:AddedOn)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindparam(":ProductID",$this->ProductID);
            $stmt->bindparam(":UserID",$this->UserID);
            $stmt->bindparam(":Qty",$this->Qty);
            $stmt->bindparam(":AddedOn",$this->AddedOn);
            if($stmt->execute())
                return true;
            return false;
        }
        
    }

    function DeleteFromCart($id){
        $query = "DELETE FROM tblcart WHERE CartID=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":id",$id);
        if($stmt->execute())
            return true;
        return false;

    }

    function AddQty($id,$qty){
        $query = "UPDATE tblcart SET Qty=Qty+:Qty WHERE CartID=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":id",$id);
        $stmt->bindparam(":Qty",$qty);
        if($stmt->execute())
            return true;
        return false;
    }

    function CheckOut($id,$Addressid){

        $stmt = $this->DisplayCart($id);
        $CartProductData = $stmt;
        $num = $stmt->rowCount();
        $TotalAmt = 0;
        if($num>0){
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                $Amt = $Qty * $Price;
                $TotalAmt += $Amt;
            }
            $time = date('Y-m-d H:i:s');
            $query = "INSERT INTO tblorders(UserID,AddressID,OrderedOn,TotalAmount)
                        Values (:userid,addressid,:orderedon,:amt)";
             $stmt = $this->conn->prepare($query);
             $stmt->bindparam(":userid",$id);
             $stmt->bindparam(":addressid",$Addressid);
             $stmt->bindparam(":orderedon",$time);
             $stmt->bindparam(":amt",$TotalAmt);
             if($stmt->execute()){
                $lastid= $this->conn->lastinsertid();
                $status = "Pending";
                $Active = 1;
                $num = $CartProductData->rowCount();
                if($num>0){
                    while ($row = $CartProductData->fetch(PDO::FETCH_ASSOC)){
                        extract($row);
                        $query = "INSERT INTO tblorderdetails(OrderID, ProductID, Qty, Price, Status, IsActive)
                                    VALUES (:OrderID,:ProductID,:Qty,:Price,:Status,:IsActive)";
                         $stmt1 = $this->conn->prepare($query);
                         $stmt1->bindparam(":OrderID",$lastid);
                         $stmt1->bindparam(":ProductID",$ProdId);
                         $stmt1->bindparam(":Qty",$Qty);
                         $stmt1->bindparam(":Price",$Price);
                         $stmt1->bindparam(":Status",$status);
                         $stmt1->bindparam(":IsActive",$Active);
                         if($stmt1->execute())
                            return true;
                        return false;
                    }
                }
             }else{
                return false;
             }
        }else{
            return false;
        }
       

    }


}




?>