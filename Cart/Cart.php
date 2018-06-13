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
                $query = "UPDATE tblcart SET Qty=Qty+:qty WHERE CartID=:id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindparam(":qty",$this->Qty);
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

        $query = "SELECT CurrentStock from tblcart c 
                    LEFT JOIN tblproduct as p on c.ProductID=p.ProductID 
                    WHERE CartID=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":id",$id);
        $stmt->execute();
        $qtydata = $stmt->fetch(PDO::FETCH_ASSOC);

        //echo $qtydata['CurrentStock'];  
        if($qtydata['CurrentStock'] >= $qty){
            $query = "UPDATE tblcart SET Qty=:Qty WHERE CartID=:id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindparam(":id",$id);
            $stmt->bindparam(":Qty",$qty);
            if($stmt->execute())
                return true;
            return false;
        }else{
            return false;
        }
    }

    function CheckOut($id,$Addressid,$Amt){ 
        
        $stmt = $this->DisplayCart($id);
        $num = $stmt->rowCount();
        if($num>0){
    
            $j=0;
            $ntavialable = array();
            $ntavialable["records"]=array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                $query = "SELECT * FROM tblproduct WHERE ProductID=:id";
                $stmt1 = $this->conn->prepare($query);
                $stmt1->bindparam(":id",$ProductID);
                $stmt1->execute();
                $qtydata = $stmt1->fetch(PDO::FETCH_ASSOC);
                //echo $qtydata['CurrentStock'] . " " . $Qty;  
                if($qtydata['CurrentStock'] < $Qty){
                    $item = array(
                        "CartID" => $CartID,
                        "ProductID" => $ProductID,
                        "ProductName" => $ProductName,
                        "CurrentStock" => $CurrentStock
                    );
                    array_push($ntavialable["records"], $item);
                    $j=1;
                }

                
            }

            if($j==1){
                $ntavialable["key"] = "sp";
                return json_encode($ntavialable);
            }
                
        }

            $time = date('Y-m-d H:i:s');
            $query = "INSERT INTO tblorders(UserID,AddressID,OrderedOn,TotalAmount)
                        Values (:userid,:addressid,:orderedon,:amt)";
             $stmt = $this->conn->prepare($query);
             $stmt->bindparam(":userid",$id);
             $stmt->bindparam(":addressid",$Addressid);
             $stmt->bindparam(":orderedon",$time);
             $stmt->bindparam(":amt",$Amt);
             if($stmt->execute()){
                $lastid= $this->conn->lastinsertid();
                $status = "Pending";
                $Active = 1;
                $CartProductData = $this->DisplayCart($id);
                while ($row = $CartProductData->fetch(PDO::FETCH_ASSOC)){
                    
                    extract($row);
                        $query = "UPDATE tblproduct SET CurrentStock=CurrentStock-:stock WHERE ProductID=:id";
                        $stmt1 = $this->conn->prepare($query);
                        $stmt1->bindparam(":id",$ProductID);
                        $stmt1->bindparam(":stock",$Qty);
                        $stmt1->execute();

                        $query = "SELECT * FROM tbldiscount WHERE ProdID=:id AND IsActive=1";
                        $stmt1 = $this->conn->prepare($query);
                        $stmt1->bindparam(":id",$ProductId);
                        $stmt1->execute();
                        //echo $ProductId;
                
                        $num = $stmt1->rowcount();
                        if($num>0){
                            $DiscountData = $stmt1->fetch(PDO::FETCH_ASSOC);
                            if(isset($DiscountData['Flat'])){
                                $Price = $Price - $DiscountData['Flat'];
                            }
                               
                            if(isset($DiscountData['Percentage'])){
                                $Price = $Price - ($Price * $DiscountData['Percentage'])/100;
                            }
                                
                        }

                        $query = "INSERT INTO tblorderdetails(OrderID, ProductID, Qty, Price, Status, IsActive, OrderUpdatedOn)
                                    VALUES (:OrderID,:ProductID,:Qty,:Price,:Status,:IsActive,:time)";
                        $stmt1 = $this->conn->prepare($query);
                        $stmt1->bindparam(":OrderID",$lastid);
                        $stmt1->bindparam(":ProductID",$ProductID);
                        $stmt1->bindparam(":Qty",$Qty);
                        $stmt1->bindparam(":Price",$Price);
                        $stmt1->bindparam(":Status",$status);
                        $stmt1->bindparam(":IsActive",$Active);
                        $stmt1->bindparam(":time",$time);
                        $stmt1->execute();
                        $OrderDetailsId = $this->conn->lastinsertid();

                        $status = "Pending";
                        $text = "Order has been Placed.";
                        $datetime = date('Y-m-d H:i:s');
                        $query = "INSERT INTO tbltracking(OrderDetailsID,TrackingText,ArrivedTime,DispatchedTime,Status) 
                                values(:OrderDetailsID,:TrackingText,:ArrivedTime,:DispatchedTime,:Status)";
                        $stmt1 = $this->conn->prepare($query);
                        $stmt1->bindparam(":OrderDetailsID",$OrderDetailsId);
                        $stmt1->bindparam(":TrackingText",$text);
                        $stmt1->bindparam(":ArrivedTime",$datetime);
                        $stmt1->bindparam(":DispatchedTime",$datetime);
                        $stmt1->bindparam(":Status",$status);
                        $stmt1->execute();

                        $Notification = new Notification($this->conn);
                        $Notification->URL = "/orderDetail";
                        $Notification->Type = $ShopID;
                        $Notification->Image = "fa-file-alt";
                        $Notification->IsRead = "0";
                        $Notification->NotificationText = "Order Arrived.";
                        $Notification->CreatedOn = date('Y-m-d H:i:s');
                        $Notification->AddNotification();

                        $query = "DELETE FROM tblcart WHERE CartID=:id";
                        $stmt1 = $this->conn->prepare($query);
                        $stmt1->bindparam(":id",$CartID);
                        $stmt1->execute();

                       
                    }
                    return '{"key":"true"}';
            }else{
                return '{"key":"false"}';
            }
                    
                 

    }


}




?>