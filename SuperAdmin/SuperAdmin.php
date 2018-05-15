<?php

include_once '../config/database.php';

class SuperAdmin {
 
    // database connection and table name
    private $conn;
    private $table_name = "tbladmin";
 
    // object properties
    public $id;
    public $AdminName;
    public $AdminImage;
    public $phone_no;
    public $email;
    public $password;
    public $oldpassword;
    public $createdon;
    public $passwordupdatedon;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function ReadInfo(){
 
        // select all query
        $query = "SELECT * FROM " . $this->table_name;

        // prepare query statement
        // echo $query;
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function CheckLogin($username,$password){

        $query = "SELECT * FROM " . $this->table_name . " WHERE ( PhoneNo = '" . $username . "' OR Email = '" . $username . "') 
                    AND Password = '" . $password . "'";

         //echo $query;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $num = $stmt->rowcount();
        if($num>0){
            $query = "UPDATE tbladmin SET IsSessionActive = 1 WHERE Email = '" . $username . "' OR PhoneNo = '" . $username . "'";
            //echo $query;
            $stmtupdate = $this->conn->prepare($query);
            if(!$stmtupdate->execute()){
                echo '{"Key" : "session Failed"}';
                return null;
            }
            return $stmt;
        }else{
            return null;
        }
       

    }

    function ChangePassword($Adminid, $newpassword, $oldsendPassword){

        $query = "SELECT * FROM " . $this->table_name . " WHERE AdminId = " . $Adminid;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);

        if($oldsendPassword == $Password){
            if ($Password == $newpassword ){
                return '{ "key" : "same" }';
            }elseif($newpassword == $OldPassword)
                return '{ "key" : "oldsame" }';
            else{
                $query = "UPDATE " . $this->table_name . " SET Password = :newpassword , OldPassword = :password 
                        WHERE AdminId = :adminid";
                //echo $query;
                $stmt = $this->conn->prepare($query);
                $stmt->bindparam(':newpassword',$newpassword);
                $stmt->bindparam(':password',$Password);
                $stmt->bindparam(':adminid',$Adminid);
                $stmt->execute();
                
                if($stmt->rowcount() > 0) {
                    return '{ "key" : "true" }';
                }else{
                    return '{ "key" : "false" }';
                }
    
            }
        }else{
            return '{ "key" : "incorrect" }';
        }

          
    }

    function UpdateInfo(){

        $query = "UPDATE " . $this->table_name . "SET AdminName=:adminname, AdminImage=:adminimage, PhoneNo=:phonene,
                    Email=:email WHERE AdminId=:adminid";
     
        echo $query;
        // prepare query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->AdminName=htmlspecialchars(strip_tags($this->AdminName));
        $this->AdminImage=htmlspecialchars(strip_tags($this->AdminImage));
        $this->phone_no=htmlspecialchars(strip_tags($this->phone_no));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->id=htmlspecialchars(strip_tags($this->id));
      
     
        // bind values
        $stmt->bindParam(":adminname", $this->AdminName);
        $stmt->bindParam(":adminimage", $this->AdminImage);
        $stmt->bindParam(":phonene", $this->phone_no);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":adminid", $this->id);
        
     
        // execute query
        if($stmt->execute()){
            return true;
        }
     
        return false;
    }

    function ForgetPassword($Adminid,$username){

        $query = "SELECT * FROM " . $this->table_name . " WHERE AdminId = " . $Adminid;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);


        if($username == $PhoneNo || $username == $Email ){

            $to = $Email;
            $subject = "Reset Your Password";
            $body = "The verification Code is 852963";
            mail($to,$subject,$body, "From: rtemp2520@gmail.com");
        }

    }

    
}




?>