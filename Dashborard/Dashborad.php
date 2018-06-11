<?php

include_once '../config/database.php';
include_once '../phpmailer/Mailer/Mail.php';


class Dashboard {
 
    // database connection and table name
    private $conn;

     // constructor with $db as database connection
     public function __construct($db){
        $this->conn = $db;
    }

    function GETCounts($tablename){
        $Count = 0;
        $query = "SELECT Count(*) Count FROM ". $tablename;
       // echo $query;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $num = $stmt->rowcount();
        if($num>0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            //echo $row['Count'];
            $Count = $row['Count'];
        }
        return $Count;
    }

    function SuperAdminDashboard(){
        $TotalProducts = $this->GETCounts('tblproduct');
        $TotalUsers = $this->GETCounts('tbluser');
        $TotalOrders = $this->GETCounts('tblorderdetails');
        $TotalShops = $this->GETCounts('tblshops');

        $items = array(
            "Products" => $TotalProducts,
            "Users" => $TotalUsers,
            "Orders" => $TotalOrders,
            "Shops" => $TotalShops
        );

        return $items;
    }

    function NewUsers(){
        $query = "SELECT UserID,Name,CreatedOn,ProfileImage FROM tbluser ORDER BY UserID DESC LIMIT 8";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function NewShops(){
        $query = "SELECT ShopID,ShopName,CreatedOn,LogoImage FROM tblshops ORDER BY ShopID DESC LIMIT 8";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}




?>