<?php

include_once '../config/database.php';

class SuperAdmin {
 
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

    function Create($id){

        if($id == "new"{
            $query = "INSERT INTO " . this->table_name . "(`id`, `Name`, `Logo`, `LogoAlt`, `Email`, `PhoneNo`,
                     `AboutUs`, `ContactUs`, `FacebookLink`, `TwitterLink`, `InstagramLink`, `YoutubeLink`, `GSTNo`, 
                     `CreatedOn`, `TagLine`) values (:id,:Name,:Logo,:Logoalt,:Email,:PhoneNo,:AbouUs,:ContactUs,:FacebookLink,
                     :TwitterLink,:InstagramLink,:YoutubeLink,:GstNo,:CreatedOn,:TagLine);" ;
        )else{
            $query = "UPDATE " . $this->table_name . "SET Name=:Name,Logo=:Logo, Logoalt=:Logoalt, Email=:Email, PhoneNo=:PhoneNo
                        AbouUs=:AbouUs, ContactUs=:ContactUs, FacebookLink=:FacebookLink, TwitterLink=:TwitterLink,
                        InstagramLink=:InstagramLink, YoutubeLink=:YoutubeLink, GstNo=:GstNo, TagLine=:TagLine, 
                        LastUpdatedOn:=LastUpdatedOn WHERE id=:id"
        }
       
     
        echo $query;
        // prepare query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->Name=htmlspecialchars(strip_tags($this->Name));
        $this->Logo=htmlspecialchars(strip_tags($this->Logo));
        $this->LogoAlt=htmlspecialchars(strip_tags($this->LogoAlt));
        $this->Email=htmlspecialchars(strip_tags($this->Email));
        $this->PhoneNo=htmlspecialchars(strip_tags($this->PhoneNo));
        $this->AboutUs=htmlspecialchars(strip_tags($this->AboutUs));
        $this->ContactUs=htmlspecialchars(strip_tags($this->ContactUs));
        $this->FacebookLink=htmlspecialchars(strip_tags($this->FacebookLink));
        $this->YoutubeLink=htmlspecialchars(strip_tags($this->YoutubeLink));
        $this->TwitterLink=htmlspecialchars(strip_tags($this->TwitterLink));
        $this->InstagramLink=htmlspecialchars(strip_tags($this->InstagramLink));
        $this->GSTNo=htmlspecialchars(strip_tags($this->GSTNo));
        $this->TagLine=htmlspecialchars(strip_tags($this->TagLine));
        $this->CreatedOn = date('Y-m-d H:i:s');
        $this->$lastUpdatedOn = date('Y-m-d H:i:s');
      
     
        // bind values
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":Name", $this->Name);
        $stmt->bindParam(":Logo", $this->Logo);
        $stmt->bindParam(":LogoAlt", $this->LogoAlt);
        $stmt->bindParam(":Email", $this->Email);
        $stmt->bindParam(":PhoneNo", $this->PhoneNo);
        $stmt->bindParam(":AboutUs", $this->AboutUs);
        $stmt->bindParam(":ContactUs", $this->ContactUs);
        $stmt->bindParam(":FacebookLink", $this->FacebookLink);
        $stmt->bindParam(":YoutubeLink", $this->YoutubeLink);
        $stmt->bindParam(":TwitterLink", $this->TwitterLink);
        $stmt->bindParam(":InstagramLink", $this->InstagramLink);
        $stmt->bindParam(":TagLine", $this->TagLine);
        $stmt->bindParam(":CreatedOn", $this->CreatedOn);
        $stmt->bindParam(":lastUpdatedOn", $this->lastUpdatedOn);
        $stmt->bindParam(":GSTNo", $this->GSTNo);
        
        // execute query
        if($stmt->execute()){
            return true;
        }
     
        return false;
    }

   

    
}




?>