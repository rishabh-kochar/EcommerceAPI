<?php

include_once '../config/database.php';
include_once '../phpmailer/Mailer/Mail.php';


class Notification {
 
    // database connection and table name
    private $conn;
    private $table_name = "tblnotification";
 
    // object properties
    public $NotificationID;
    public $URL;
    public $Type;
    public $Image;
    public $IsRead;
    public $NotificationText;
    public $CreatedOn;

    public function __construct($db){
        $this->conn = $db;
    }

    function AddNotification(){
        $query = "INSERT INTO tblnotification(URL,Type,Image,IsRead,NotificationText,CreatedOn) 
                    VALUES(:URL,:Type,:Image,:IsRead,:NotificationText,:CreatedOn)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":URL",$this->URL);
        $stmt->bindparam(":Type",$this->Type);
        $stmt->bindparam(":Image",$this->Image);
        $stmt->bindparam(":IsRead",$this->IsRead);
        $stmt->bindparam(":CreatedOn",$this->CreatedOn);
        $stmt->bindparam(":NotificationText",$this->NotificationText);
        if($stmt->execute())
            return true;
        return false;
    }

    function GetSuperAdminNoti(){
        $query = "SELECT * FROM tblnotification WHERE IsRead=0 AND Type=1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;

    }

    function GetSellerNoti(){
        $query = "SELECT * FROM tblnotification WHERE IsRead=0 AND Type=0";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;

    }

    

    function ReadNotification($id){
        $query = "UPDATE tblnotification SET IsRead=1 WHERE NotificationID=:id";
        //echo $query;
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":id",$id);
        if($stmt->execute())
            return true;
        return false;
    }

    function ClearAllNotification($Type){
        $query = "UPDATE tblnotification SET ISRead=1 WHERE Type=:Type";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":Type",$Type);
        if($stmt->execute())
            return true;
        return false;
    }
    


}




?>