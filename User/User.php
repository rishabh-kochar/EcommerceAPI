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
        $query = "SELECT *,u.Name UserName,u.PhoneNo UserPhoneNo,a.Name AddressPhoneNo, a.Name AddressName 
                    FROM tbluser u
                    LEFT JOIN tblorders as o on u.UserID = o.UserID
                    LEFT JOIN tblAddress as a on o.AddressID = a.AddressID
                    WHERE o.UserID=:id AND o.OrderID=:oid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":id",$cid);
        $stmt->bindparam(":oid",$id);
        $stmt->execute();
        return $stmt;
    }

    function UserSignUp(){
        $query = "INSERT INTO tbluser(PhoneNo,Email,Password,Gender,Name,CreatedOn,IsActive)
                    values(:PhoneNo,:Email,:Password,:Gender,:Name,:CreatedOn,:IsActive)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindparam(":PhoneNo",$this->PhoneNo);
        $stmt->bindparam(":Email",$this->Email);
        $stmt->bindparam(":Password",$this->Password);
        $stmt->bindparam(":Gender",$this->Gender);
        $stmt->bindparam(":Name",$this->Name);
        $stmt->bindparam(":CreatedOn",$this->CreatedOn);
        $stmt->bindparam(":IsActive",$this->IsActive);
        if($stmt->execute())
            return true;
        return false;
    }

    function UserData($id){
        $query = "SELCT * FROM tbluser WHERE UserID=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":id",$this->$id);
        $stmt->execute();
        return $stmt;

    }

    function CheckLogin($Username,$Password){
        $query = "SELECT * FROM tbluser WHERE PhoneNo=:PhoneNo OR Email=:Email AND Password=:Password AND IsActive=1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":PhoneNo",$this->$Username);
        $stmt->bindparam(":Email",$this->$Username);
        $stmt->bindparam(":Password",$this->$Password);
        $stmt->execute();
        return $stmt;
    }

    function AllUserData(){
        $query = "SELECT * FROM tbluser";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function SetStatus($id,$status){
        $query = "UPDATE tbluser SET IsActive=:status WHERE UserID=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":status",$status);
        $stmt->bindparam(":id",$id);
        if( $stmt->execute())
            return true;
        return false;
    } 

    

    
}
?>