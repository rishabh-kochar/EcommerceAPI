<?php

include_once '../config/database.php';
require("../phpmailer/Mailer/PHPMailer_5.2.0/class.PHPMailer.php");

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
    public $logo;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function ReadInfo($id){
 
        // select all query
        $query = "SELECT * FROM " . $this->table_name . " WHERE Adminid = :id OR Email = :id OR PhoneNo=:id;";

        // prepare query statement
        // echo $query;
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":id",$id);

        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function CheckLogin($username,$password){

        $query = "SELECT * FROM " . $this->table_name . " WHERE ( PhoneNo = :username OR Email = :username) 
                    AND Password = :password ";
         //echo $query;
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":username",$username);
        $stmt->bindparam(":password",$password);
        $stmt->execute();
        return $stmt;
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
                        , PasswordUpdatedOn=:passwordupdatedon WHERE AdminId = :adminid";
                //echo $query;
                $date = date('Y-m-d H:i:s');
                $stmt = $this->conn->prepare($query);
                $stmt->bindparam(':newpassword',$newpassword);
                $stmt->bindparam(':password',$Password);
                $stmt->bindparam(':adminid',$Adminid);
                $stmt->bindparam(':passwordupdatedon',$date);
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

        $query = "UPDATE " . $this->table_name . " SET AdminName=:adminname, PhoneNo=:phoneno,
                    Email=:email WHERE AdminId=:adminid";
     
        //echo $query;
        // prepare query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->AdminName=htmlspecialchars(strip_tags($this->AdminName));
        $this->phone_no=htmlspecialchars(strip_tags($this->phone_no));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->id=htmlspecialchars(strip_tags($this->id));
      
        //echo $this->AdminName;
        //echo $this->phone_no;

        // bind values
        $stmt->bindParam(":adminname", $this->AdminName);
        $stmt->bindParam(":phoneno", $this->phone_no);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":adminid", $this->id);
        
        
        // execute query
        if($stmt->execute()){
            return true;
        }
     
        return false;
    }

    function ForgetPassword($username){

        $query = "SELECT * FROM " . $this->table_name . " WHERE Email = :username OR PhoneNo = :username"; 
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":username",$username);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);

       
        

        if($username == $PhoneNo || $username == $Email ){


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
                $message = '<h1>Hello ' . $AdminName . ',</h1>';
                $message .= '<p>To Reset Password <a href="http://localhost:4200/reset?rand=' . $randomString . '&type=admin">Click Here</a></p>';
                $message .= '<p> Your Verification Code is : <b>' . $generatedPassword . '</b> </p>';
        
                

                // $message is the user's message they typed in
                // on our contact us page. We set this variable at
                // the top of this page with:
                // $message = $_REQUEST['message'] ;
                $mail->Body    = $message;
                $mail->AltBody = $message;

                if(!$mail->Send())
                    return "0";
                else{
                    $query = "UPDATE " . $this->table_name . " SET VerificationCode = '" . $generatedPassword ."' WHERE Email = '" . $username . "' OR PhoneNo = '" . $username . "'"; 
                    $stmt = $this->conn->prepare($query);
                    $stmt->execute();
                    return "1";
                }
        }else{
            return "2";
        }

       

    }

    function randompassword( $length ) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $password = substr( str_shuffle( $chars ), 0, $length );
        return $password;
    }

    function ResetPassword($newpassword,$username,$verificationcode){

        $query = "SELECT * FROM " . $this->table_name . " WHERE (Email = :username OR 
                    PhoneNo = :username) AND VerificationCode = :verify"; 
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":username",$username);
        $stmt->bindparam(":verify",$verificationcode);
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
                , PasswordUpdatedOn=:passwordupdatedon, RandomString='' WHERE AdminId = :adminid";
                //echo $query;
                $stmt = $this->conn->prepare($query);
                $stmt->bindparam(':newpassword',$newpassword);
                $stmt->bindparam(':password',$row['Password']);
                $stmt->bindparam(':adminid',$row['AdminId']);
                $stmt->bindparam(':passwordupdatedon',$time);
                $stmt->execute();
                return "1";
            }
            
        }else{
            return "0";
        }

    }

    function RandomString($rand){
        $query = "SELECT * FROM " . $this->table_name . " WHERE RandomString =:rand"; 
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":rand",$rand);
        $stmt->execute();

        $num = $stmt->rowcount();
        if($num>0){
            return $stmt;

        }else{
            return null;
        }

    }

    function AdminImageUpdate(){

        $query = "UPDATE " . $this->table_name . " SET AdminImage=:Adminimage WHERE AdminId=:adminid";
     
        //echo $query;
        // prepare query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->AdminImage=htmlspecialchars(strip_tags($this->AdminImage));
        
        // bind values
        $stmt->bindParam(":Adminimage", $this->AdminImage);
        $stmt->bindParam(":adminid", $this->id);
        
     
        // execute query
        if($stmt->execute()){
            return true;
        }
     
        return false;

    }
   
}
?>