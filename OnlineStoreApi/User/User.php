<?php

include_once '../config/database.php';
require_once '../Notification/Notification.php';

class User {
 
    // database connection and table name
    private $conn;
    private $table_name = "tbluser";
    private $database;
 
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
    public $ProfileImage;

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
		$this->database = new Database();
    }

    function UserDetailsOrder($id,$cid){
        $query = "SELECT *,u.Name UserName,u.PhoneNo UserPhoneNo,a.Name AddressPhoneNo, a.Name AddressName 
                    FROM tbluser u
                    LEFT JOIN tblorders as o on u.UserID = o.UserID
                    LEFT JOIN tbladdress as a on o.AddressID = a.AddressID
                    WHERE o.UserID=:id AND o.OrderID=:oid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":id",$cid);
        $stmt->bindparam(":oid",$id);
        $stmt->execute();
        return $stmt;
    }

    function UserSignUp(){
        $query = "INSERT INTO tbluser(PhoneNo,Email,Password,Gender,Name,CreatedOn,IsActive,ProfileImage)
                    values(:PhoneNo,:Email,:Password,:Gender,:Name,:CreatedOn,:IsActive,:image)";
        $stmt = $this->conn->prepare($query);
        $image="index.png";
        $stmt->bindparam(":PhoneNo",$this->PhoneNo);
        $stmt->bindparam(":Email",$this->Email);
        $stmt->bindparam(":Password",$this->Password);
        $stmt->bindparam(":Gender",$this->Gender);
        $stmt->bindparam(":Name",$this->Name);
        $stmt->bindparam(":CreatedOn",$this->CreatedOn);
        $stmt->bindparam(":IsActive",$this->IsActive);
        $stmt->bindparam(":image",$image);
        if($stmt->execute()){
            $Notification = new Notification($this->conn);
            $Notification->URL = "/userdata";
            $Notification->Type = "0";
            $Notification->Image = "fa-user";
            $Notification->IsRead = "0";
            $Notification->NotificationText = $this->Name . " Joined.";
            $Notification->CreatedOn = date('Y-m-d H:i:s');
            $Notification->AddNotification();
            return true;
        }
            
        return false;
    }

    function UserData($id){
        $query = "SELECT * FROM tbluser WHERE UserID=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":id",$id);
        $stmt->execute();
        return $stmt;

    }

    function CheckLogin($Username,$Password){
        $query = "SELECT * FROM tbluser WHERE (PhoneNo=:PhoneNo OR Email=:Email) AND Password=:Password AND IsActive=1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":PhoneNo",$Username);
        $stmt->bindparam(":Email",$Username);
        $stmt->bindparam(":Password",$Password);
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

    function ForgetPassword($userName){
        $query = "SELECT * FROM " . $this->table_name . " WHERE  
                    Email = :Username OR PhoneNo = :Username"; 
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":Username",$userName);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
       
        
        if($userName == $row['Email'] || $userName == $row['PhoneNo'] ){


                $query = "SELECT * FROM tblwebsite WHERE id = 1";
                $websitestmt = $this->conn->prepare($query);
                $websitestmt->execute();
                $websitedata = $websitestmt->fetch(PDO::FETCH_ASSOC);
                $mail = new SendMail();
                
                // Random String
                do{

                    $randomString = $this->randompassword(10);
                    $query = "UPDATE " . $this->table_name . " SET RandomString = '" . $randomString ."', 
                                RandomStringTime = '" . date('Y-m-d H:i:s') . "' WHERE Email = '" . $userName . "' OR 
                                PhoneNo = '" . $userName . "'";
                    //echo $query;
                    $stmt = $this->conn->prepare($query);

                }while(!$stmt->execute());

                $Subject = "Reset Password";
                $generatedPassword = $this->randompassword(8);
                $message = '<h1>Hello ' . $row['Name'];
                $message .= '<p>To Reset Password <a href="http://'. $this->database->ClientDomain() .'/reset?rand=' . $randomString . '&type=shop">Click Here</a></p>';
                $message .= '<p> Your Verification Code is : <b>' . $generatedPassword . '</b> </p>';
        
            

                if(!$mail->send($row['Email'],$Subject,$message))
                {
               
                    
                return "0";
    
                }else{
                    $query = "UPDATE " . $this->table_name . " SET VerificationCode = '" . $generatedPassword ."' WHERE Email = '" . $userName . "'"; 
                $stmt = $this->conn->prepare($query);
                $stmt->execute();

                return "1";
                }

                
        
        }else{
            return "2";
        }
    }

    function ResetPassword($newpassword,$username,$verificationcode){

        $query = "SELECT * FROM " . $this->table_name . " WHERE  
                 (Email = '" . $username . "' OR 
                 PhoneNo = '" . $username . "') AND VerificationCode = '" . $verificationcode . "'"; 
        //echo $query;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        
        $num = $stmt->rowcount();
        if($num>0){

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if($row['Password'] == $newpassword){
                return "2";
            }elseif($row['OldPassword'] == $newpassword){
                return "3";
            }else{
                $time = date('Y-m-d H:i:s');

                $query = "UPDATE " . $this->table_name . " SET Password = :newpassword , OldPassword = :password 
                , PasswordUpdatedOn=:passwordupdatedon, RandomString='' WHERE UserID = :adminid";
                //echo $query;
                $stmt = $this->conn->prepare($query);
                $stmt->bindparam(':newpassword',$newpassword);
                $stmt->bindparam(':password',$row['Password']);
                $stmt->bindparam(':adminid',$row['UserID']);
                $stmt->bindparam(':passwordupdatedon',$time);
                $stmt->execute();
                return "1";
            }
            
        }else{
            return "0";
        }

    }

    function RandomString($rand){
        $query = "SELECT * FROM " . $this->table_name . " WHERE RandomString ='" . $rand ."'"; 
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $num = $stmt->rowcount();
        if($num>0){
            return $stmt;

        }else{
            return null;
        }

    }

    function ChangePassword($Id,$newpassword, $oldsendPassword){

        $query = "SELECT * FROM " . $this->table_name . " WHERE UserID = " . $Id;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);
        $time = date('Y-m-d H:i:s');
        if($oldsendPassword == $Password){
            if ($Password == $newpassword ){
                return '{ "key" : "same" }';
            }elseif($newpassword == $OldPassword)
                return '{ "key" : "oldsame" }';
            else{
                $query = "UPDATE " . $this->table_name . " SET Password = :newpassword , OldPassword = :password 
                        , PasswordUpdatedOn=:passwordupdatedon WHERE UserID = :adminid";
                //echo $query;
                $stmt = $this->conn->prepare($query);
                $stmt->bindparam(':newpassword',$newpassword);
                $stmt->bindparam(':password',$Password);
                $stmt->bindparam(':adminid',$Id);
                $stmt->bindparam(':passwordupdatedon',$time);
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

    function randompassword( $length ) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $password = substr( str_shuffle( $chars ), 0, $length );
        return $password;
    }

    function ViewAllAddress($id){
        $query = "SELECT * FROM tbladdress WHERE UserID=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":id",$id);
        $stmt->execute();
        return $stmt;
    }

    function ViewSingleAddress($id){
        $query = "SELECT * FROM tbladdress WHERE AddressID=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":id",$id);
        $stmt->execute();
        return $stmt;
    }

    function AddAddress(){
        $id = $this->AddressID;
        if($id == "new"){
            $query = "INSERT INTO tbladdress(UserID, Name, PhoneNo, Pincode, Locality, Address, 
                        City, State, Landmark, Country, AddressType, IsActive, CreatedOn) 
                            VALUES (:UserID,:Name,:PhoneNo,:Pincode,:Locality,:Address,
                            :City,:State,:Landmark,:Country,:AddressType,:IsActive,:CreatedOn)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindparam(":UserID",$this->UserID);
            $stmt->bindparam(":Name",$this->AddressName);
            $stmt->bindparam(":PhoneNo",$this->AddressPhoneNo);
            $stmt->bindparam(":Pincode",$this->Pincode);
            $stmt->bindparam(":Locality",$this->Locality);
            $stmt->bindparam(":Address",$this->Address);
            $stmt->bindparam(":City",$this->City);
            $stmt->bindparam(":State",$this->State);
            $stmt->bindparam(":Landmark",$this->Landmark);
            $stmt->bindparam(":Country",$this->Country);
            $stmt->bindparam(":AddressType",$this->AddressType);
            $stmt->bindparam(":IsActive",$this->IsActive);
            $stmt->bindparam(":CreatedOn",$this->CreatedOn);
            if($stmt->execute())
                return true;
            return false;

        }else{
            $query = "UPDATE tbladdress UserID=:UserID, Name=:Name, PhoneNo=:PhoneNo, Pincode=:Pincode, 
                        Locality=:Locality, Address=:Address, 
                        City=:City, State=:State, Landmark=:Landmark, Country=:Country, AddressType=:AddressType
                          WHERE AddressID=:id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindparam(":id",$id);
            $stmt->bindparam(":UserID",$this->UserID);
            $stmt->bindparam(":Name",$this->AddressName);
            $stmt->bindparam(":PhoneNo",$this->AddressPhoneNo);
            $stmt->bindparam(":Pincode",$this->Pincode);
            $stmt->bindparam(":Locality",$this->Locality);
            $stmt->bindparam(":Address",$this->Address);
            $stmt->bindparam(":City",$this->City);
            $stmt->bindparam(":State",$this->State);
            $stmt->bindparam(":Landmark",$this->Landmark);
            $stmt->bindparam(":Country",$this->Country);
            $stmt->bindparam(":AddressType",$this->AddressType);
          
            if($stmt->execute())
                return true;
            return false;
        }
    }

    function UserImageUpdate(){

        $query = "UPDATE " . $this->table_name . " SET ProfileImage=:Adminimage WHERE UserID=:id";
     
        //echo $query;
        // prepare query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->ProfileImage=htmlspecialchars(strip_tags($this->ProfileImage));
        
        // bind values
        $stmt->bindParam(":Adminimage", $this->ProfileImage);
        $stmt->bindParam(":id", $this->UserID);
        
     
        // execute query
        if($stmt->execute()){
            return true;
        }
        return false;

    }

    function UpdateProfile(){
        $query = "UPDATE tbluser SET Email=:Email, PhoneNo=:PhoneNo, Gender=:Gender, Name=:Name 
                    WHERE UserID=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":Email", $this->Email);
        $stmt->bindParam(":PhoneNo", $this->PhoneNo);
        $stmt->bindParam(":Gender", $this->Gender);
        $stmt->bindParam(":Name", $this->Name);
        $stmt->bindParam(":id", $this->UserID);
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    function CheckEmail($Username){
        $query = "SELECT * FROM tbluser WHERE Email=:Username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":Username",$Username);
        $stmt->execute();
        return $stmt;
    }

    function CheckPhoneNo($Username){
        $query = "SELECT * FROM tbluser WHERE PhoneNo=:Username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":Username",$Username);
        $stmt->execute();
        return $stmt;
    }
   
    function RemoveImage($id){
        
        $query = "UPDATE " . $this->table_name . " SET ProfileImage=:Adminimage WHERE UserID=:id";
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->ProfileImage="index.png";
        
        // bind values
        $stmt->bindParam(":Adminimage", $this->ProfileImage);
        $stmt->bindParam(":id", $id);
        
        if($stmt->execute()){
            return true;
        }
        return false;
    }


    

    
}
?>