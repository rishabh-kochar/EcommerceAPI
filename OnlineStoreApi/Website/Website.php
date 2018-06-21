<?php

include_once '../config/database.php';

class Website {
 
    // database connection and table name
    private $conn;
    private $table_name = "tblwebsite";
 
    // object properties
    public $id;
    public $Name;
    public $Logo;
    public $LogoAlt;
    public $Email;
    public $PhoneNo;
    public $Password;
    public $TagLine;
    public $AboutUs;
    public $ContactUs;
    public $FacebookLink;
    public $YoutubeLink;
    public $TwitterLink;
    public $InstagramLink;
    public $GSTNo;
    public $CreatedOn;
    public $lastUpdatedOn;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function ReadEmail($id){

        $query = "SELECT * FROM " . $this->table_name . " WHERE id = " . $id;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function Create($id){

        if($id == "new"){
            $query = "INSERT INTO " . $this->table_name . "(Name, PhoneNo,
                     AboutUs, ContactUs, FacebookLink, TwitterLink, InstagramLink, YoutubeLink, GSTNo, 
                     CreatedOn, TagLine) values (:Name,:PhoneNo,:AboutUs,:ContactUs,:FacebookLink,
                     :TwitterLink,:InstagramLink,:YoutubeLink,:GSTNo,:CreatedOn,:TagLine);" ;
        }else{
            $query = "UPDATE " . $this->table_name . " SET Name=:Name, PhoneNo=:PhoneNo,
                        AboutUs=:AboutUs, ContactUs=:ContactUs, FacebookLink=:FacebookLink, TwitterLink=:TwitterLink,
                        InstagramLink=:InstagramLink, YoutubeLink=:YoutubeLink, GSTNo=:GSTNo, TagLine=:TagLine, 
                        LastUpdatedOn=:LastUpdatedOn WHERE id=:id";
        }
       
     
        //echo $query;
        // prepare query
        $stmt = $this->conn->prepare($query);
     
        if($id == "new"){
            $stmt->bindParam(":CreatedOn", $this->CreatedOn);
        }else{
            $stmt->bindParam(":id", $this->id);
            $stmt->bindParam(":LastUpdatedOn", $this->lastUpdatedOn);
        }
        // bind values
     
        $stmt->bindParam(":Name", $this->Name);
        $stmt->bindParam(":PhoneNo", $this->PhoneNo);
        $stmt->bindParam(":AboutUs", $this->AboutUs);
        $stmt->bindParam(":ContactUs", $this->ContactUs);
        $stmt->bindParam(":FacebookLink", $this->FacebookLink);
        $stmt->bindParam(":YoutubeLink", $this->YoutubeLink);
        $stmt->bindParam(":TwitterLink", $this->TwitterLink);
        $stmt->bindParam(":InstagramLink", $this->InstagramLink);
        $stmt->bindParam(":TagLine", $this->TagLine);
        $stmt->bindParam(":GSTNo", $this->GSTNo);
       
        
        // execute query
        if($stmt->execute()){
            return true;
        }
     
        return false;
    }

   function mailSetting(){

    $query = "UPDATE " . $this->table_name . " SET Email=:Email, Password=:Password WHERE id=:id";

    $stmt = $this->conn->prepare($query);

    $this->Email=htmlspecialchars(strip_tags($this->Email));
    $this->Password=htmlspecialchars(strip_tags($this->Password));

    $stmt->bindParam(":Email", $this->Email);
    $stmt->bindParam(":Password", $this->Password);
    $stmt->bindParam(":id", $this->id);

    if($stmt->execute()){
        return true;
    }
 
    return false;

   }

   function Websitelogo(){

    $query = "UPDATE " . $this->table_name . " SET Logo=:logo, LogoAlt=:logoalt WHERE Id=:id";
     
        //echo $query;
        // prepare query
        $stmt = $this->conn->prepare($query);
        //echo $query;
        // sanitize
        //echo $this->Logo;
        //echo $this->LogoAlt;
        //echo $this->id;
        $this->Logo=htmlspecialchars(strip_tags($this->Logo));
        $this->LogoAlt=htmlspecialchars(strip_tags($this->LogoAlt));
        
        // bind values
        $stmt->bindParam(":logo", $this->Logo);
        $stmt->bindParam(":logoalt", $this->LogoAlt);
        $stmt->bindParam(":id", $this->id);
        
     
        // execute query
        if($stmt->execute()){
            return true;
        }
     
        return false;

   }

   function GetWebInfo(){
    $query = "SELECT * FROM " . $this->table_name . ";";

    $stmt = $this->conn->prepare($query);

    // execute query
    $stmt->execute();

    return $stmt;
   }

    
}
?>