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
        $stmt->bindparam(":ArrivedTime",$this->DispatchedTime);
        $stmt->bindparam(":Status",$Status);

        if($stmt->execute())
            return true;
        return false;
        
    }

    function ViewTracking($id){
        $query = "SELECT * FROM tbltracking t
                    WHERE OrderDetailsID=:id ORDER BY ArrivedTime";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":id",$id);
        $stmt->execute();
        return $stmt;


    }
    
}
?>