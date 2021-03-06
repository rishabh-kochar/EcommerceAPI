<?php

include_once '../config/database.php';
require_once '../Notification/Notification.php';
require_once '../phpmailer/Mailer/Mail.php';


class Shops {
 
    // database connection and table name
    private $conn;
    private $table_name = "tblshops";
    private $database;
 
    // object properties
    public $ShopID;
    public $ShopName;
    public $Tagline;
    public $LogoImage;
    public $LogoAlt;
    public $Address;
    public $City;
    public $State;
    public $Pincode;
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
        $database = new Database();
    }

    function SignUp(){
  
            $query = "INSERT INTO " . $this->table_name . "(ShopName,PhoneNo,Email,OwnerName,IsActive,CreatedOn,ShopType,
            UserName,Password,LogoImage)
            values(:ShopName,:PhoneNo,:Email,:OwnerName,:IsActive,:CreatedOn,:ShopType,:Username,:Password,:LogoImage); ";
            $stmt = $this->conn->prepare($query);

            // sanitize
            $this->ShopName=htmlspecialchars(strip_tags($this->ShopName));
            $this->PhoneNo=htmlspecialchars(strip_tags($this->PhoneNo));
            $this->Email=htmlspecialchars(strip_tags($this->Email));
            $this->OwnerName=htmlspecialchars(strip_tags($this->OwnerName));
            $this->ShopType=htmlspecialchars(strip_tags($this->ShopType));
          
            //echo $this->AdminName;
            //echo $this->phone_no;
            $IsActive = 0;
            $image = "Default.png";
            // bind values
            $stmt->bindParam(":ShopName", $this->ShopName);
            $stmt->bindParam(":PhoneNo", $this->PhoneNo);
            $stmt->bindParam(":Email", $this->Email);
            $stmt->bindParam(":OwnerName", $this->OwnerName);
            $stmt->bindParam(":ShopType", $this->ShopType);
            $stmt->bindParam(":IsActive", $IsActive);
            $stmt->bindParam(":CreatedOn", $this->CreatedOn);
            $stmt->bindParam(":Username", $randomUsername);
            $stmt->bindParam(":Password", $randompassword);
            $stmt->bindparam(":LogoImage",$image);
            
            
            
            // execute query
            if($stmt->execute()){

                $query = "SELECT * FROM tblwebsite WHERE id = 1";
                $websitestmt = $this->conn->prepare($query);
                $websitestmt->execute();
                $websitedata = $websitestmt->fetch(PDO::FETCH_ASSOC);
                $mail = new SendMail();

            
                $Subject = "Welcome To" . $websitedata['Name'] ;
                $message = '<h1>Hello ' . $this->OwnerName . ',</h1>';
                $message .= '<p>Welcome to ' . $websitedata['Name'] . '. Your requested has been forwarded to the Administrator. 
                                You will Receive Response in Very short time.</p>';
                

          

                if(!$mail->send($this->Email,$Subject,$message))
                    return '{"key":"Error: ' . $mail->ErrorInfo . '"}';
                $Notification = new Notification($this->conn);
                $Notification->URL = "/shops";
                $Notification->Type = "0";
                $Notification->Image = "fa-store";
                $Notification->IsRead = "0";
                $Notification->NotificationText = $this->ShopName . " Requested to Approve.";
                $Notification->CreatedOn = date('Y-m-d H:i:s');
                $Notification->AddNotification();
                return '{"key":"true"}';
                
            }
            return '{"key":"false"}';
    }

    function randompassword( $length ) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $password = substr( str_shuffle( $chars ), 0, $length );
        return $password;
    }

    function ShopData(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE IsMember=1 ORDER BY ShopID DESC;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function NewShopData(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE IsMember=0 ORDER BY ShopID DESC;";
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
        $stmt->bindparam(":id",$id);
        $stmt->bindparam(":status",$status);
        if( $stmt->execute())
            return true;
        return false;
    }

    function SetShopApprovedStatus($id,$status){

        $query = "SELECT * FROM " . $this->table_name . " WHERE ShopID=:id;";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":id",$id);
        $stmt->execute();
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);

        if($IsMember == 0){
            

            $query = "SELECT * FROM tblwebsite WHERE id = 1";
            $websitestmt = $this->conn->prepare($query);
            $websitestmt->execute();
            $websitedata = $websitestmt->fetch(PDO::FETCH_ASSOC);
            $mail = new SendMail();

            // Random String
            do{

                $randomUsername = $this->randompassword(8);
                $query = "SELECT * FROM " . $this->table_name . " WHERE Username = '" . $randomUsername . "'";                   //echo $query;
                $stmt = $this->conn->prepare($query);
                $stmt->execute();

            }while($stmt->rowcount()>0);

            $randompassword = $this->randompassword(8);
           
            

      
            $Subject = "Welcome To " . $websitedata['Name'] ;
            $message = '<h1>Hello ' . $this->OwnerName . ',</h1>';
            $message .= '<p>Welcome to ' . $websitedata['Name'] . '</p>';
            $message .= '<p>The Login credential are as follows - </p>';
            $message .= '<p> Username : <b>' . $randomUsername . '</b> </p>';
            $message .= '<p> Password : <b>' . $randompassword . '</b> </p>';
            $message .= '<p><a href="'. $this->database->AdminDomain() .'/login">Click Here To Login</a></p>';


            if(!$mail->send($Email,$Subject,$message))
            {
                return '{"key":"Error: ' . $mail->ErrorInfo . '"}';

            }else{
                $query = "UPDATE " . $this->table_name . " SET IsApproved=:status, IsMember=:status, UserName=:UserName, Password=:Password WHERE ShopID=:id;";
                $stmt = $this->conn->prepare($query);
                $stmt->bindparam(":UserName",$randomUsername);
                $stmt->bindparam(":Password",$randompassword);
            }


        }else{
            $query = "UPDATE " . $this->table_name . " SET IsApproved=:status WHERE ShopID=:id;";
            $stmt = $this->conn->prepare($query);
        }

        //echo $query;
       
        $stmt->bindparam(":id",$id);
        $stmt->bindparam(":status",$status);
        
        if( $stmt->execute()){
                $Notification = new Notification($this->conn);
                $Notification->URL = "/shopProfile";
                $Notification->Type = $id;
                $Notification->Image = "fa-handshake";
                $Notification->IsRead = "0";
                $Notification->NotificationText = "Welcome to " . $websitedata['Name'] . ".";
                $Notification->CreatedOn = date('Y-m-d H:i:s');
                $Notification->AddNotification();
                return '{"key":"true"}';
        }
            
        return '{"key":"false"}';
    }

    function DeleteShop($id){
        $query = "DELETE FROM " . $this->table_name . " WHERE ShopID=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":id",$id);
        //echo $query;
        if( $stmt->execute())
            return true;
        return false;
    }

    function CheckLogin($username,$password){

        $query = "SELECT * FROM " . $this->table_name . " WHERE (UserName = :username OR Email = :username
                    OR PhoneNo = :username) AND Password = :password
                    AND IsApproved = 1";

         //echo $query;
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":username",$username);
        $stmt->bindparam(":password",$password);
        $stmt->execute();
        return $stmt;
        
       
    }

    function ChangePassword($Id,$newpassword, $oldsendPassword){

        $query = "SELECT * FROM " . $this->table_name . " WHERE ShopID = " . $Id;
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
                        , PasswordUpdatedOn=:passwordupdatedon WHERE ShopID = :adminid";
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

    function ForgetPassword($username){

        $query = "SELECT * FROM " . $this->table_name . " WHERE UserName = '" . $username . "' OR 
                    Email = '" . $username. "' OR PhoneNo = '" . $username. "'"; 
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
       
        
        if($username == $row['UserName'] || $username == $row['Email'] || $username == $row['PhoneNo'] ){


                $query = "SELECT * FROM tblwebsite WHERE id = 1";
                $websitestmt = $this->conn->prepare($query);
                $websitestmt->execute();
                $websitedata = $websitestmt->fetch(PDO::FETCH_ASSOC);
                $mail = new SendMail();
                
                // Random String
                do{

                    $randomString = $this->randompassword(10);
                    $query = "UPDATE " . $this->table_name . " SET RandomString = '" . $randomString ."', 
                                RandomStringTime = '" . date('Y-m-d H:i:s') . "' WHERE Email = '" . $username . "' OR 
                                PhoneNo = '" . $username . "' OR UserName = '" . $username . "'";
                    //echo $query;
                    $stmt = $this->conn->prepare($query);

                }while(!$stmt->execute());


                $Subject = "Reset Password";
                $generatedPassword = $this->randompassword(8);
                $message = '<h1>Hello ' . $row['ShopName']  . '('. $row['OwnerName'] .'),</h1>';
                $message .= '<p>To Reset Password <a href="http://'. $this->database->AdminDomain() .'/reset?rand=' . $randomString . '&type=shop">Click Here</a></p>';
                $message .= '<p> Your Verification Code is : <b>' . $generatedPassword . '</b> </p>';
        
            

                if(!$mail->send($row['Email'],$Subject,$message))
                {
               
                    
                return "0";
    
                }else{
                    $query = "UPDATE " . $this->table_name . " SET VerificationCode = '" . $generatedPassword ."' 
                                    WHERE Username = '" . $username . "'"; 
                $stmt = $this->conn->prepare($query);
                $stmt->execute();

                return "1";
                }

                
        
        }else{
            return "2";
        }
    }

    function ResetPassword($newpassword,$username,$verificationcode){

        $query = "SELECT * FROM " . $this->table_name . " WHERE Username = '" . $username . 
                 "' OR Email = '" . $username . "' OR 
                 PhoneNo = '" . $username . "' AND VerificationCode = '" . $verificationcode . "'"; 
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
                , PasswordUpdatedOn=:passwordupdatedon, RandomString='' WHERE ShopID = :adminid";
                //echo $query;
                $stmt = $this->conn->prepare($query);
                $stmt->bindparam(':newpassword',$newpassword);
                $stmt->bindparam(':password',$row['Password']);
                $stmt->bindparam(':adminid',$row['ShopID']);
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

    function SingleShop($id){
        $query = "SELECT * FROM " . $this->table_name . " WHERE ShopID=:id OR Email=:id OR PhoneNo=:id;";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":id",$id);
        //echo $query;
        $stmt->execute();
        return $stmt;
    }

    function UpdateShopInfo(){
        $query = "UPDATE " . $this->table_name . " SET ShopName=:ShopName, ShopType=:ShopType,
                    PhoneNo=:PhoneNo, Email=:Email, Address=:Address, City=:City, State=:State,
                    Pincode=:Pincode  WHERE ShopID=:id";
        $stmt = $this->conn->prepare($query);

                    // sanitize
        $this->ShopName=htmlspecialchars(strip_tags($this->ShopName));
        $this->PhoneNo=htmlspecialchars(strip_tags($this->PhoneNo));
        $this->Email=htmlspecialchars(strip_tags($this->Email));
        $this->Address=htmlspecialchars(strip_tags($this->Address));
        $this->ShopType=htmlspecialchars(strip_tags($this->ShopType));
        $this->City=htmlspecialchars(strip_tags($this->City));
        $this->State=htmlspecialchars(strip_tags($this->State));
        $this->Pincode=htmlspecialchars(strip_tags($this->Pincode));

        $stmt->bindParam(":ShopName", $this->ShopName);
        $stmt->bindParam(":PhoneNo", $this->PhoneNo);
        $stmt->bindParam(":Email", $this->Email);
        $stmt->bindParam(":Address", $this->Address);
        $stmt->bindParam(":City", $this->City);
        $stmt->bindParam(":ShopType", $this->ShopType);
        $stmt->bindParam(":State", $this->State);
        $stmt->bindParam(":Pincode", $this->Pincode);
        $stmt->bindparam(":id", $this->ShopID);

        if($stmt->execute())
            return true;
        return false;
        
    }

    function UpdateBusinessDetails(){
        $query = "UPDATE " . $this->table_name . " SET GSTNo=:GSTNo, LogoImage=:LogoImage,
                    LogoAlt=:LogoAlt, Tagline=:Tagline WHERE ShopID=:id";
        
                    $stmt = $this->conn->prepare($query);

                    // sanitize
        $this->GSTNo=htmlspecialchars(strip_tags($this->GSTNo));
        $this->LogoImage=htmlspecialchars(strip_tags($this->LogoImage));
        $this->LogoAlt=htmlspecialchars(strip_tags($this->LogoAlt));
        $this->Tagline=htmlspecialchars(strip_tags($this->Tagline));

        $stmt->bindParam(":GSTNo", $this->GSTNo);
        $stmt->bindParam(":LogoImage", $this->LogoImage);
        $stmt->bindParam(":LogoAlt", $this->LogoAlt);
        $stmt->bindParam(":Tagline", $this->Tagline);
        $stmt->bindparam(":id", $this->ShopID);

        if($stmt->execute())
            return true;
        return false;
    }

    function UpdateSocialLinks(){
        $query = "UPDATE " . $this->table_name . " SET FacebookLink=:FacebookLink, YoutubeLink=:YoutubeLink,
        TwitterLink=:TwitterLink, InstagramLink=:InstagramLink WHERE ShopID=:id";

        $stmt = $this->conn->prepare($query);

        $this->FacebookLink=htmlspecialchars(strip_tags($this->FacebookLink));
        $this->YoutubeLink=htmlspecialchars(strip_tags($this->YoutubeLink));
        $this->TwitterLink=htmlspecialchars(strip_tags($this->TwitterLink));
        $this->InstagramLink=htmlspecialchars(strip_tags($this->InstagramLink));

        $stmt->bindParam(":FacebookLink", $this->FacebookLink);
        $stmt->bindParam(":YoutubeLink", $this->YoutubeLink);
        $stmt->bindParam(":TwitterLink", $this->TwitterLink);
        $stmt->bindParam(":InstagramLink", $this->InstagramLink);
        $stmt->bindparam(":id", $this->ShopID);
        if($stmt->execute())
            return true;
        return false;
    }

    function InitialSetup(){
        $query = "UPDATE " . $this->table_name . " SET Username=:Username, Password=:Password, IsInitialSetup=1 WHERE ShopID=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":Username",$this->Username);
        $stmt->bindparam(":Password",$this->Password);
        $stmt->bindparam(":id",$this->ShopID);
        if($stmt->execute()){
            $stmt = $this->SingleShop($this->ShopID);
            $num = $stmt->rowcount();
            if($num>0){
                $shop_arr=array();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($row);
                $shop_arr=array(
                    "key" => "true",
                    "ShopID" => $ShopID,
                    "ShopName" => $ShopName,
                    "Tagline" => $Tagline,
                    "LogoImage" => $LogoImage,
                    "Address" => $Address,
                    "City" => $City,
                    "State" => $State,
                    "PhoneNo" => $PhoneNo,
                    "Email" => $Email,
                    "Website" => $Website,
                    "OwnerName" => $OwnerName,
                    "FacebookLink" => $FacebookLink,
                    "InstagramLink" => $InstagramLink,
                    "TwitterLink" => $TwitterLink,
                    "YoutubeLink" => $YoutubeLink,
                    "LogoAlt" => $LogoAlt,
                    "GSTNo" => $GSTNo,
                    "UserName" => $UserName,
                    "Password" => $Password,
                    "OldPassword" => $OldPassword,
                    "PasswordUpdatedOn" => $PasswordUpdatedOn,
                    "IsActive" => $IsActive,
                    "CreatedOn" => $CreatedOn,
                    "ShopType" => $ShopType,
                    "ApprovedOn" => $ApprovedOn,
                    "IsSessionActive" => $IsSessionActive,
                    "IsApproved" => $IsApproved,
                    "Pincode" => $Pincode
                );

                return json_encode($shop_arr);

            }else{
                return null;
            }
        }
        return null;
    }

    function SameUsername($Username){
        $query = "SELECT * FROM tblshops WHERE Username=:Username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":Username",$Username);
        $stmt->execute();
        return $stmt;
    }
    
}
?>