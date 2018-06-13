<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: content-type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
date_default_timezone_set('Asia/Calcutta'); 
error_reporting(E_ALL);
ini_set('display_errors','1');


class Database{
 
    // specify your own database credentials
    private $host = "localhost";
    private $db_name = "onlinestore";
    private $username = "root";
    private $password = "";

    // private $host = "209.99.16.19:3306";
    // private $db_name = "OnlineStore";
    // private $username = "Rishabh";
    // private $password = "Loveyourself2509";
    public $conn;
 
    // get the database connection
    public function getConnection(){
 
        $this->conn = null;
 
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Connection Succesful";
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
 
        return $this->conn;
    }

    public function notification_time($d1){

        $d1 = strtotime($d1);
        $d2 = strtotime(date('Y-m-d H:i:s'));
        $diff = ($d2 - $d1);
        if($diff <= '60')
            return round($diff,0) . " seconds ago.";
        elseif($diff <= '3600')
            return round($diff/60,0) . " minutes ago.";
        elseif($diff <= '172800')
            return round(($diff/60)/60,0) . " Hours ago.";
        else
            return round((($diff/60)/60)/60,0) . " days ago";
    }
}


?>