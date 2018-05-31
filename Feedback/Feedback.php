<?php

include_once '../config/database.php';
include_once '../phpmailer/Mailer/Mail.php';


class Feedback {
 
    // database connection and table name
    private $conn;
    private $table_name = "tblfeedback";
 
    // object properties
    public $FeedbackID;
    public $Name;
    public $Email;
    public $Type;
    public $Response;
    public $Feedback;
    public $CretedOn;
    public $RepliedOn;
    public $subject;

     // constructor with $db as database connection
     public function __construct($db){
        $this->conn = $db;
    }

    function AddFeedback(){
        $query = "INSERT INTO "  . $this->table_name . " (Name,Email,Type,Feedback,CreatedOn) Values(:Name,:Email,:Type,
        :Feedback,:CreatedOn)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":Name", $this->Name);
        $stmt->bindParam(":Email", $this->Email);
        $stmt->bindParam(":Type", $this->Type);
        $stmt->bindParam(":Feedback", $this->Feedback);
        $stmt->bindParam(":CreatedOn", $this->CreatedOn);

        if($stmt->execute())
            return true;
        return false;

    }

    function GetFeedback(){
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY FeedbackID DESC";
        //echo $query;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }


    function Reply(){

        $mail = new SendMail();
        $query = "SELECT * FROM tblfeedback WHERE FeedbackID=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":id",$this->FeedbackID);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);
        if($mail->send($Email,$this->subject,$this->Response)){
            $query = "UPDATE " . $this->table_name . " SET Response=:Response, RepliedOn=:RepliedOn WHERE FeedbackID=:id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindparam(":id",$this->FeedbackID);
            $stmt->bindparam(":Response",$this->Response);
            $stmt->bindparam(":RepliedOn",$this->RepliedOn);
            if($stmt->execute())
                return true;
            return false;
        }else{
            return false;
        }
    }


    


}




?>