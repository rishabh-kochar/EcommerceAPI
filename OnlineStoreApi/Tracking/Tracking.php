<?php

include_once '../config/database.php';

class Tracking {
 
    // database connection and table name
    private $conn;
    private $table_name = "tbltracking";
 
    // object properties
    public $TrackingID;
    public $OrderDetailsID;
    public $TrackingText;
    public $ArrivedTime;
    public $DispatchedTime;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function AddTracking(){
        $Status = "Transist";
        $query = "INSERT INTO tbltracking(OrderDetailsID,TrackingText,ArrivedTime,DispatchedTime,Status) 
                    values(:OrderDetailsID,:TrackingText,:ArrivedTime,:DispatchedTime,:Status);";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":OrderDetailsID",$this->OrderDetailsID);
        $stmt->bindparam(":TrackingText",$this->TrackingText);
        $stmt->bindparam(":ArrivedTime",$this->ArrivedTime);
        $stmt->bindparam(":DispatchedTime",$this->DispatchedTime);
        $stmt->bindparam(":Status",$Status);

        if($stmt->execute())
            return true;
        return false;
        
    }

    function ViewTracking($id,$sid){
        //echo $sid;
        if($sid == 0){
            $query = "SELECT * FROM tbltracking t
                    WHERE OrderDetailsID=:id ORDER BY ArrivedTime";
            $stmt = $this->conn->prepare($query);
            $stmt->bindparam(":id",$id);
            $stmt->execute();
            return $stmt;
        }else{
            $query = "SELECT * FROM tbltracking t
                    LEFT JOIN tblorderdetails as od on t.OrderDetailsID = od.OrderDetailID
                    LEFT JOIN tblproduct as p on od.ProductID = p.ProductID
                    LEFT JOIN tblcategory as c on p.CategoryID = c.CategoryID 
                    WHERE OrderDetailsID=:id AND p.ShopID=:sid ORDER BY ArrivedTime";
            $stmt = $this->conn->prepare($query);
            $stmt->bindparam(":id",$id);
            $stmt->bindparam(":sid",$sid);
            $stmt->execute();
            return $stmt;
        }
        


    }
    
}
?>