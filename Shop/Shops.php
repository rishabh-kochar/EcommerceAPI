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


        $query = "SELECT * FROM tblwebsite WHERE id = 1";
        $websitestmt = $this->conn->prepare($query);
        $websitestmt->execute();
        $websitedata = $websitestmt->fetch(PDO::FETCH_ASSOC);
        $mail = new PHPMailer();

        // set mailer to use SMTP
        $mail->IsSMTP();

        // As this email.php script lives on the same server as our email server
        // we are setting the HOST to localhost
        $mail->Host = "smtp.gmail.com";  // specify main and backup server

        $mail->SMTPAuth = true;     // turn on SMTP authentication
        $mail->Port=465;
        $mail->SMTPSecure = "ssl";
        // When sending email using PHPMailer, you need to send from a valid email address
        // In this case, we setup a test email account with the following credentials:
        // email: send_from_PHPMailer@bradm.inmotiontesting.com
        // pass: password
        if(!isset($websitedata['Email']) || !isset($websitedata['Password']))
            return "0";

        $mail->Username = $websitedata['Email'];  // SMTP username
        $mail->Password = $websitedata['Password']; // SMTP password

        // $email is the user's email address the specified
        // on our contact us page. We set this variable at
        // the top of this page with:
        // $email = $_REQUEST['email'] ;
        $mail->From = $websitedata['Email'] ;
        $mail->FromName = $websitedata['Name'] ;

        // Random String
        do{

            $randomUsername = $this->randompassword(8);
            $query = "SELECT * FROM " . $this->table_name . " WHERE Username = '" . $randomUsername . "'";                   //echo $query;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

        }while($stmt->rowcount()>0);

        $randompassword = $this->randompassword(8);
        // below we want to set the email address we will be sending our email to.
        $mail->AddAddress($this->Email);


        $mail->IsHTML(true);

        $mail->Subject = "Welcome To" . $websitedata['Name'] ;
        $message = '<h1>Hello ' . $this->OwnerName . ',</h1>';
        $message .= '<p>Welcome to ' . $websitedata['Name'] . '</p>';
        $message .= '<p>The Login credential are as follows - </p>';
        $message .= '<p> Username : <b>' . $randomUsername . '</b> </p>';
        $message .= '<p> Password : <b>' . $randompassword . '</b> </p>';
        $message .= '<p><a href="http://localhost:4200/login">Click Here To Login</a></p>';

        

        // $message is the user's message they typed in
        // on our contact us page. We set this variable at
        // the top of this page with:
        // $message = $_REQUEST['message'] ;
        $mail->Body    = $message;
        $mail->AltBody = $message;

        if(!$mail->Send())
        {
            //echo "Message could not be sent. <p>";
            //echo "Mailer Error: " . $mail->ErrorInfo;
            
            return '{"key":"Error: ' . $mail->ErrorInfo . '"}';

        }else{
          
            $query = "INSERT INTO " . $this->table_name . "(ShopName,PhoneNo,Email,OwnerName,IsActive,CreatedOn,ShopType,
            UserName,Password)
            values(:ShopName,:PhoneNo,:Email,:OwnerName,:IsActive,:CreatedOn,:ShopType,:Username,:Password); ";
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
            $stmt->bindParam(":Username", $randomUsername);
            $stmt->bindParam(":Password", $randompassword);
            
            
            
            // execute query
            if($stmt->execute()){
                return '{"key":"true"}';
            }
        
            return '{"key":"false"}';
        }


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
            $query = "UPDATE " . $this->table_name . " SET IsApproved=:status, IsMember=:status WHERE ShopID=:id;";
        }else{
            $query = "UPDATE " . $this->table_name . " SET IsApproved=:status WHERE ShopID=:id;";
        }

        //echo $query;
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":id",$id);
        $stmt->bindparam(":status",$status);
        if( $stmt->execute())
            return true;
        return false;
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

    function ForgetPassword($username){

        $query = "SELECT * FROM " . $this->table_name . " WHERE UserName = '" . $username . "'"; 
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        

       
        
        if($username == $row['Username'] ){


                $query = "SELECT * FROM tblwebsite WHERE id = 1";
                $websitestmt = $this->conn->prepare($query);
                $websitestmt->execute();
                $websitedata = $websitestmt->fetch(PDO::FETCH_ASSOC);
                $mail = new PHPMailer();

                // set mailer to use SMTP
                $mail->IsSMTP();

                // As this email.php script lives on the same server as our email server
                // we are setting the HOST to localhost
                $mail->Host = "smtp.gmail.com";  // specify main and backup server

                $mail->SMTPAuth = true;     // turn on SMTP authentication
                $mail->Port=465;
                $mail->SMTPSecure = "ssl";
                // When sending email using PHPMailer, you need to send from a valid email address
                // In this case, we setup a test email account with the following credentials:
                // email: send_from_PHPMailer@bradm.inmotiontesting.com
                // pass: password
                $mail->Username = $websitedata['Email'];  // SMTP username
                $mail->Password = $websitedata['Password']; // SMTP password

                // $email is the user's email address the specified
                // on our contact us page. We set this variable at
                // the top of this page with:
                // $email = $_REQUEST['email'] ;
                $mail->From = $websitedata['Email'] ;
                $mail->FromName = $websitedata['Name'] ;

                // Random String
                do{

                    $randomString = $this->randompassword(10);
                    $query = "UPDATE " . $this->table_name . " SET RandomString = '" . $randomString ."', RandomStringTime = '" . date('Y-m-d H:i:s') . "' WHERE Email = '" . $username . "' OR PhoneNo = '" . $username . "'";
                    //echo $query;
                    $stmt = $this->conn->prepare($query);

                }while(!$stmt->execute());


                // below we want to set the email address we will be sending our email to.
                $mail->AddAddress($Email);


                $mail->IsHTML(true);

                $mail->Subject = "Reset Password";
                $generatedPassword = $this->randompassword(8);
                $message = '<h1>Hello ' . $row['ShopName']  . '('. $row['OwnerName'] .'),</h1>';
                $message .= '<p>To Reset Password <a href="http://localhost:4200/reset?rand=' . $randomString . '&type=shop">Click Here</a></p>';
                $message .= '<p> Your Verification Code is : <b>' . $generatedPassword . '</b> </p>';
        
                

                // $message is the user's message they typed in
                // on our contact us page. We set this variable at
                // the top of this page with:
                // $message = $_REQUEST['message'] ;
                $mail->Body    = $message;
                $mail->AltBody = $message;

                if(!$mail->Send())
                {
                //echo "Message could not be sent. <p>";
                //echo "Mailer Error: " . $mail->ErrorInfo;
                    
                return "0";
    
                }else{
                    $query = "UPDATE " . $this->table_name . " SET VerificationCode = '" . $generatedPassword ."' WHERE Username = '" . $username . "'"; 
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
                 "' AND VerificationCode = '" . $verificationcode . "'"; 
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
        $query = "SELECT * FROM " . $this->table_name . " WHERE ShopID = " . $id;
        $stmt = $this->conn->prepare($query);
        //echo $query;
        $stmt->execute();
        return $stmt;
    }
    

    
}




?>