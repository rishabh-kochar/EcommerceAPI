<?php

include_once '../config/database.php';


class Order {
 
    // database connection and table name
    private $conn;
    private $table_name = "tblOrders";
     
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
        $this->ShopID = 2;
        $query = "SELECT *,od.ProductID PID FROM tblorderdetails od
                    LEFT JOIN tblproduct as p on od.ProductID = p.ProductID
                    LEFT JOIN tblCategory as c on p.CategoryID = c.CategoryID
                    LEFT JOIN tblorders as o on od.OrderID = o.OrderID
                    LEFT JOIN tbluser as u on o.UserID = u.UserID
                    LEFT JOIN tbladdress as a on u.UserID = a.UserID
                    WHERE c.ShopID=:shopid
                    ORDER BY OrderDetailID DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":shopid",$this->ShopID);
        $stmt->execute();
        return $stmt;
    }

    
}
?>