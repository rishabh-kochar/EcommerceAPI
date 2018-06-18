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

    function TotalOrderValue($id){
        if($id==0){
            $query = "SELECT sum(Price*Qty) amt FROM tblorderdetails;";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['amt'];
        }else{
            $query = "SELECT sum(od.Price*Qty) amt FROM tblorderdetails od
                        LEFT JOIN tblproduct as p on p.ProductID = od.ProductID
                        WHERE ShopID=:id;";
            $stmt = $this->conn->prepare($query);
            $stmt->bindparam(":id",$id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['amt'];
        }
        
    }

    function SellerOrderCount($id){
        $query = "SELECT Count(*) cnt FROM tblorderdetails od
                    LEFT JOIN tblorders as o on od.OrderID = o.OrderID
                    LEFT JOIN tblproduct p on od.ProductID = p.ProductID
                    WHERE p.ShopID=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":id",$id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['cnt'];
    }

    function SellerproductCount($id){
        $query = "SELECT count(*) cnt FROM tblproduct
                    WHERE ShopID=:id AND IsApproved = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":id",$id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['cnt'];
    }

    function SellerDashboard($id){
        $TotalProducts = $this->SellerProductCount($id);
        $TotalUsers = $this->GETCounts('tbluser');
        $TotalOrders = $this->SellerOrderCount($id);
        $TotalValue = $this->TotalOrderValue($id);

        $items = array(
            "Products" => $TotalProducts,
            "Users" => $TotalUsers,
            "Orders" => $TotalOrders,
            "Value" => $TotalValue
        );

        return $items;
    }
}




?>