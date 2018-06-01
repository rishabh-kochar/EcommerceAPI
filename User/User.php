<?php

include_once '../config/database.php';
require("../phpmailer/Mailer/PHPMailer_5.2.0/class.PHPMailer.php");


class User {
 
    // database connection and table name
    private $conn;
    private $table_name = "tbluser";
 
    // object properties
    public $UserID;
    public $PhoneNO;
    public $Email;
    public $Gender;
    public $Name;
    public $CreatedOn;
    public $IsActive;
    public $VerificationCode;
    public $Password;

    //Address Details
    public $AddressID;
    public $AddressName;
    public $AddressPhoneNo;
    public $Pincode;
    public $Locality;
    public $Address;
    public $City;
    public $State;
    public $Landmark;
    public $Country;
    public $AddressType;



    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function UserDetailsOrder($id,$cid){
        $query = "SELECT *,u.Name UserName,u.PhoneNo UserPhoneNo,a.Name AddressPhoneNo, a.Name AddressName FROM tbluser u
                        LEFT JOIN tblorders as o on u.UserID = o.UserID
                     LEFT JOIN tblAddress as a on o.AddressID = a.AddressID
                     
                     WHERE o.UserID=:id AND o.OrderID=:oid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":id",$cid);
        $stmt->bindparam(":oid",$id);
        $stmt->execute();
        return $stmt;
    }

    

    
}
?>