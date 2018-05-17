<?php

include_once '../config/database.php';

class SuperAdmin {
 
    // database connection and table name
    private $conn;
    private $table_name = "tblshops";
 
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

    

    
}




?>