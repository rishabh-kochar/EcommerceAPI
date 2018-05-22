<?php

include_once '../config/database.php';


class Shops {
 
    // database connection and table name
    private $conn;
    private $table_name = "tblshops";
 
    // object properties
    public $ShopID;
    public $ShopName;
    public $Tagline;
    public $LogoImage;
    public $LogoAlt;
    public $Address;
    public $City;
    public $State;
    public $PhoneNo;
    public $Email;
    public $Website;
    public $OwnerName;
    public $FacebookLink;
    public $YoutubeLink;
    public $TwitterLink;
    public $InstagramLink;
    public $GSTNo;
    public $Username;
    public $Password;
    public $IsActive;
    public $CreatedOn;
    public $ApprovedOn;
    public $ShopType;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function SignUp(){

        $query = "INSERT INTO " . $this->table_name . "(ShopName,PhoneNo,Email,OwnerName,IsActive,CreatedOn,ShopType)
                    values(:ShopName,:PhoneNo,:Email,:OwnerName,:IsActive,:CreatedOn,:ShopType); ";
                    $stmt = $this->conn->prepare($query);
     
                    // sanitize
                    $this->ShopName=htmlspecialchars(strip_tags($this->ShopName));
                    $this->PhoneNo=htmlspecialchars(strip_tags($this->PhoneNo));
                    $this->Email=htmlspecialchars(strip_tags($this->Email));
                    $this->OwnerName=htmlspecialchars(strip_tags($this->OwnerName));
                    $this->ShopType=htmlspecialchars(strip_tags($this->ShopType));
                  
                    //echo $this->AdminName;
                    //echo $this->phone_no;
            
                    // bind values
                    $stmt->bindParam(":ShopName", $this->ShopName);
                    $stmt->bindParam(":PhoneNo", $this->PhoneNo);
                    $stmt->bindParam(":Email", $this->Email);
                    $stmt->bindParam(":OwnerName", $this->OwnerName);
                    $stmt->bindParam(":ShopType", $this->ShopType);
                    $stmt->bindParam(":IsActive", 0);
                    $stmt->bindParam(":CreatedOn", $this->CreatedOn);
                    
                    
                    
                    // execute query
                    if($stmt->execute()){
                        return true;
                    }
                 
                    return false;
    }

    function ShopData(){
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY ShopID DESC;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function GetShopStatus($id){
        $query = "SELECT * FROM " . $this->table_name . " WHERE ShopID=:id;";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparams(":id",$id);
        $stmt->execute();
        $num = $stmt->rowcount();
        if($num>0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($row);
            if($IsActive){
                return true;
            }else{
                return false;
            }
        }
    }

    function SetShopStatus($id,$status){
        $query = "UPDATE " . $this->table_name . " SET IsActive=:status WHERE ShopID=:id;";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparams(":id",$id);
        $stmt->bindparams(":status",$status);
        if( $stmt->execute())
            return true;
        return false;
    }

    function CheckLogin($username,$password){

        $query = "SELECT * FROM " . $this->table_name . " WHERE UserName = '" . $username . "' AND Password = '" . $password . "'";

         //echo $query;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $num = $stmt->rowcount();
        if($num>0){
            $query = "UPDATE " . $this->table_name . " SET IsSessionActive = 1 WHERE UserName = '" . $username . "' OR PhoneNo = '" . $username . "'";
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

    function ChangePassword($Id,$newpassword, $oldsendPassword){

        $query = "SELECT * FROM " . $this->table_name . " WHERE ShopID = " . $ID;
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
                        , PasswordUpdatedOn=:passwordupdatedon WHERE ShopID = :adminid";
                //echo $query;
                $stmt = $this->conn->prepare($query);
                $stmt->bindparam(':newpassword',$newpassword);
                $stmt->bindparam(':password',$Password);
                $stmt->bindparam(':adminid',$Id);
                $stmt->bindparam(':passwordupdatedon',date('Y-m-d H:i:s'));
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

    

    
}




?>