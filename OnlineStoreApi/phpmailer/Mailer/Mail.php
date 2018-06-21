<?php

require_once('D:\INETPUB\VHOSTS\riteshtailor.in\onlinestoreapi.riteshtailor.in\OnlineStoreApi\phpmailer\Mailer\PHPMailer_5.2.0\class.phpmailer.php');
//require_once('C:\xampp\htdocs\OnlineStoreApi\phpmailer\Mailer\PHPMailer_5.2.0\class.phpmailer.php');

class SendMail {

    function send($mailto,$subject,$message){


        $database = new Database();
        $conn = $database->getConnection();

        $query = "SELECT * FROM tblwebsite WHERE id = 1";
                $websitestmt = $conn->prepare($query);
                $websitestmt->execute();
                $websitedata = $websitestmt->fetch(PDO::FETCH_ASSOC);
                $mail = new PHPMailer();

            
                $mail->IsSMTP();

                
                $mail->Host = "smtp.gmail.com";  // specify main and backup server

                $mail->SMTPAuth = true;     // turn on SMTP authentication
                $mail->Port=465;
                $mail->SMTPSecure = "ssl";

                if(!isset($websitedata['Email']) || !isset($websitedata['Password']))
                    return false;
                
                $mail->Username = $websitedata['Email'];  // SMTP username
                $mail->Password = $websitedata['Password']; // SMTP password

                $mail->From = $websitedata['Email'] ;
                $mail->FromName = $websitedata['Name'] ;
                $mail->AddAddress($mailto);


                $mail->IsHTML(true);

                $mail->Subject = $subject;
                $mail->Body    = $message;
                $mail->AltBody = $message;

                if(!$mail->Send())
                {
                //echo "Message could not be sent. <p>";
                //echo "Mailer Error: " . $mail->ErrorInfo;
                    return false;
                }else{
                    return true;
                }

    } 
}
 

?>