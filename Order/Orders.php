<?php

include_once '../config/database.php';


class Order {
 
    // database connection and table name
    private $conn;
    private $table_name = "tblorders";
     
    // object properties
    public $OrderID;
    public $UserID;
    public $AdressID;
    public $OrderedOn;
    public $TotalAmount;

    //Order Details
    public $OrderDetailsID;
    public $ProductID;
    public $Qty;
    public $Price;
    public $IsActive;
    public $OrderUpdatedOn;

    //Extra Properties
    public $ShopID;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function GetOrders(){
        //$this->ShopID = 2;
        $query = "SELECT *,od.ProductID PID,o.UserID CID FROM tblorderdetails od
                    LEFT JOIN tblproduct as p on od.ProductID = p.ProductID
                    LEFT JOIN tblcategory as c on p.CategoryID = c.CategoryID
                    LEFT JOIN tblorders as o on od.OrderID = o.OrderID
                    LEFT JOIN tbluser as u on o.UserID = u.UserID
                    LEFT JOIN tbladdress as a on u.UserID = a.UserID
                    WHERE p.ShopID=:shopid
                    ORDER BY OrderDetailID DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":shopid",$this->ShopID);
        $stmt->execute();
        return $stmt;
    }

    function OrderConfirm(){
        $status = "Confirmed";
        $query = "UPDATE tblorderdetails SET status=:status WHERE OrderDetailID=:OrderDetailID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":status", $status);
        $stmt->bindparam(":OrderDetailID", $this->OrderDetailsID);
        if($stmt->execute()){

            $text = "Your Order has Been Confirmed By the Seller.";
            $datetime = date('Y-m-d H:i:s');
            $query = "INSERT INTO tbltracking(OrderDetailsID,TrackingText,ArrivedTime,DispatchedTime,Status) 
                    values(:OrderDetailsID,:TrackingText,:ArrivedTime,:DispatchedTime,:Status);";
            $stmt = $this->conn->prepare($query);
            $stmt->bindparam(":OrderDetailsID",$this->OrderDetailsID);
            $stmt->bindparam(":TrackingText",$text);
            $stmt->bindparam(":ArrivedTime",$datetime);
            $stmt->bindparam(":DispatchedTime",$datetime);
            $stmt->bindparam(":Status",$status);


            $stmt->execute();
            return true;
        }    
        return false;
    }

    function OrderDelievered(){
        $status = "Delievered";
        $query = "UPDATE tblorderdetails SET status=:status WHERE OrderDetailID=:OrderDetailID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":status", $status);
        $stmt->bindparam(":OrderDetailID", $this->OrderDetailsID);
        if($stmt->execute()){

            $text = "Your Order has Been Delieverd to your Address.";
            $datetime = date('Y-m-d H:i:s');
            $query = "INSERT INTO tbltracking(OrderDetailsID,TrackingText,ArrivedTime,DispatchedTime,Status) 
                    values(:OrderDetailsID,:TrackingText,:ArrivedTime,:DispatchedTime,:Status);";
            $stmt = $this->conn->prepare($query);
            $stmt->bindparam(":OrderDetailsID",$this->OrderDetailsID);
            $stmt->bindparam(":TrackingText",$text);
            $stmt->bindparam(":ArrivedTime",$datetime);
            $stmt->bindparam(":DispatchedTime",$datetime);
            $stmt->bindparam(":Status",$status);


            $stmt->execute();
            return true;
        }
            
        return false;
    }

    function OrderShipped(){
        $status = "Shipped";
        $query = "UPDATE tblorderdetails SET status=:status WHERE OrderDetailID=:OrderDetailID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":status", $status);
        $stmt->bindparam(":OrderDetailID", $this->OrderDetailsID);

        if($stmt->execute()){
            $text = "Your Order has Been Shipped for your Address.";
            $datetime = date('Y-m-d H:i:s');
            $query = "INSERT INTO tbltracking(OrderDetailsID,TrackingText,ArrivedTime,DispatchedTime,Status) 
                    values(:OrderDetailsID,:TrackingText,:ArrivedTime,:DispatchedTime,:Status)";
            $stmt->bindparam(":OrderDetailsID",$this->OrderDetailsID);
            $stmt->bindparam(":TrackingText",$text);
            $stmt->bindparam(":ArrivedTime",$datetime);
            $stmt->bindparam(":DispatchedTime",$datetime);
            $stmt->bindparam(":Status",$status);

            $stmt->execute();
            return true;
        }
            
        return false;
    }

    function Allorder(){
        $query = "SELECT *,od.ProductID PID,o.UserID CID FROM tblorderdetails od
                    LEFT JOIN tblproduct as p on od.ProductID = p.ProductID
                    LEFT JOIN tblcategory as c on p.CategoryID = c.CategoryID
                    LEFT JOIN tblorders as o on od.OrderID = o.OrderID
                    LEFT JOIN tbluser as u on o.UserID = u.UserID
                    LEFT JOIN tbladdress as a on u.UserID = a.UserID
                    LEFT JOIN tblshops as s on p.ShopID = s.ShopID
                    ORDER BY OrderDetailID DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function GetUserOrders($id){
        $query = "SELECT *,od.ProductID PID,o.UserID CID,od.Price PurchasedPrice FROM tblorderdetails od
                    LEFT JOIN tblproduct as p on od.ProductID = p.ProductID
                    LEFT JOIN tblcategory as c on p.CategoryID = c.CategoryID
                    LEFT JOIN tblorders as o on od.OrderID = o.OrderID
                    LEFT JOIN tblAddress as a on o.AddressID = a.AddressID
                    LEFT JOIN tblshops as s on p.ShopID = s.ShopID
                    WHERE o.UserID=:id
                    ORDER BY OrderDetailID DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":id",$id);
        $stmt->execute();
        return $stmt;
    }

    
}
?>