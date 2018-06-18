<?php

include_once '../config/database.php';
require_once '../phpmailer/Mailer/Mail.php';
include_once '../Notification/Notification.php';


class Order {
 
    // database connection and table name
    private $conn;
    private $table_name = "tblorders";
     
    // object properties
    public $OrderID;
    public $UserID;
    public $AdressID;
    public $OrderedOn;
    public $TotalAmount;

    //Order Details
    public $OrderDetailsID;
    public $ProductID;
    public $Qty;
    public $Price;
    public $IsActive;
    public $OrderUpdatedOn;

    //Extra Properties
    public $ShopID;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function GetOrders(){
        //$this->ShopID = 2;
        $query = "SELECT *,od.ProductID PID,o.UserID CID,od.Price FPrice FROM tblorderdetails od
                    LEFT JOIN tblproduct as p on od.ProductID = p.ProductID
                    LEFT JOIN tblcategory as c on p.CategoryID = c.CategoryID
                    LEFT JOIN tblorders as o on od.OrderID = o.OrderID
                    LEFT JOIN tbluser as u on o.UserID = u.UserID
                    LEFT JOIN tbladdress as a on o.AddressID = a.AddressID
                    WHERE p.ShopID=:shopid
                    ORDER BY OrderDetailID DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":shopid",$this->ShopID);
        $stmt->execute();
        return $stmt;
    }

    function OrderConfirm(){
        $datetime = date('Y-m-d H:i:s');
        $status = "Confirmed";
        $query = "UPDATE tblorderdetails SET OrderUpdatedOn=:OrderUpdatedOn, status=:status WHERE OrderDetailID=:OrderDetailID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":status", $status);
        $stmt->bindparam(":OrderUpdatedOn", $datetime);
        $stmt->bindparam(":OrderDetailID", $this->OrderDetailsID);
        if($stmt->execute()){

            $text = "Your Order has Been Confirmed By the Seller.";
            $query = "INSERT INTO tbltracking(OrderDetailsID,TrackingText,ArrivedTime,DispatchedTime,Status) 
                    values(:OrderDetailsID,:TrackingText,:ArrivedTime,:DispatchedTime,:Status);";
            $stmt = $this->conn->prepare($query);
            $stmt->bindparam(":OrderDetailsID",$this->OrderDetailsID);
            $stmt->bindparam(":TrackingText",$text);
            $stmt->bindparam(":ArrivedTime",$datetime);
            $stmt->bindparam(":DispatchedTime",$datetime);
            $stmt->bindparam(":Status",$status);

            $Notification = new Notification($this->conn);
            $Notification->URL = "/tracking";
            $Notification->Type = "0";
            $Notification->Image = "fa-calendar-check";
            $Notification->IsRead = "0";
            $Notification->NotificationText = "Order No " . $this->OrderDetailsID . " Confirmed.";
            $Notification->CreatedOn = date('Y-m-d H:i:s');
            $Notification->AddNotification();

            $stmt->execute();
            return true;
        }    
        return false;
    }

    function OutForDelivery(){
        $datetime = date('Y-m-d H:i:s');
        $query = "SELECT *,od.Price FPrice FROM tblorderdetails od
                    LEFT JOIN tblorders as o on od.OrderID = o.OrderID
                    LEFT JOIN tblproduct as p on od.ProductID = p.ProductID
                    WHERE OrderDetailID=:OrderDetailID AND Status='Shipped'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":OrderDetailID", $this->OrderDetailsID);
        $stmt->execute();
        $num = $stmt->rowcount();
        $userData = $stmt ->fetch(PDO::FETCH_ASSOC);
        $UserID = $userData['UserID'];
        if($num == 1){
            $status = "OFD";
            $query = "UPDATE tblorderdetails SET OrderUpdatedOn=:OrderUpdatedOn, status=:status WHERE OrderDetailID=:OrderDetailID";
            $stmt = $this->conn->prepare($query);
            $stmt->bindparam(":status", $status);
            $stmt->bindparam(":OrderUpdatedOn", $datetime);
            $stmt->bindparam(":OrderDetailID", $this->OrderDetailsID);
            if($stmt->execute()){

                $text = "Your Order has Been Out For Delivery to your Address.";
                $query = "INSERT INTO tbltracking(OrderDetailsID,TrackingText,ArrivedTime,DispatchedTime,Status) 
                        values(:OrderDetailsID,:TrackingText,:ArrivedTime,:DispatchedTime,:Status);";
                $stmt = $this->conn->prepare($query);
                $stmt->bindparam(":OrderDetailsID",$this->OrderDetailsID);
                $stmt->bindparam(":TrackingText",$text);
                $stmt->bindparam(":ArrivedTime",$datetime);
                $stmt->bindparam(":DispatchedTime",$datetime);
                $stmt->bindparam(":Status",$status);

                $stmt->execute();

                $mail = new SendMail();
                $query = "SELECT * FROM tbluser WHERE UserID=:id";
                $stmt1 = $this->conn->prepare($query);
                $stmt1->bindparam(":id",$UserID);
                $stmt1->execute();
                $row = $stmt1->fetch(PDO::FETCH_ASSOC);
                extract($row);
                $Subject = "Order Out For Delivery.";
                $Message = "<p>Your Order Has Been Out For delivery.</p>";
                $Message .= "<p>" . $userData['ProductName']  . " ( " . $userData['FPrice'] ." ) * "  . $userData['Qty'] . "</p>";
                $Message .= "<p><a href='http://localhost:4200/orderDetail?ODID='" . $this->OrderDetailsID . "> Click Here To Track</a></p>";
                $Message .= "<p> <b>Order No: </b> " . $this->OrderDetailsID . "</p>";
                $mail->send($Email,$Subject,$Message);

                return '{"key":"true"}';
            }
                
            return '{"key":"false"}';
        }else{
            return '{"key":"noaccess"}';
        }
    }

    function OrderDelievered(){

        $datetime = date('Y-m-d H:i:s');
        $query = "SELECT * FROM tblorderdetails od
                    LEFT JOIN tblproduct as p on od.ProductID = p.ProductID
                    WHERE OrderDetailID=:OrderDetailID AND od.Status='OFD'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":OrderDetailID", $this->OrderDetailsID);
        $stmt->execute();
        $num = $stmt->rowcount();
        $userData = $stmt ->fetch(PDO::FETCH_ASSOC);
        $ShopID = $userData['ShopID'];
        if($num == 1){
            $status = "Delievered";
            $query = "UPDATE tblorderdetails SET OrderUpdatedOn=:OrderUpdatedOn, status=:status WHERE OrderDetailID=:OrderDetailID";
            $stmt = $this->conn->prepare($query);
            $stmt->bindparam(":status", $status);
            $stmt->bindparam(":OrderUpdatedOn", $datetime);
            $stmt->bindparam(":OrderDetailID", $this->OrderDetailsID);
            if($stmt->execute()){

                $text = "Your Order has Been Delieverd to your Address.";
                $query = "INSERT INTO tbltracking(OrderDetailsID,TrackingText,ArrivedTime,DispatchedTime,Status) 
                        values(:OrderDetailsID,:TrackingText,:ArrivedTime,:DispatchedTime,:Status);";
                $stmt = $this->conn->prepare($query);
                $stmt->bindparam(":OrderDetailsID",$this->OrderDetailsID);
                $stmt->bindparam(":TrackingText",$text);
                $stmt->bindparam(":ArrivedTime",$datetime);
                $stmt->bindparam(":DispatchedTime",$datetime);
                $stmt->bindparam(":Status",$status);


                $stmt->execute();

                $mail = new SendMail();
                $query = "SELECT * FROM tblshops WHERE ShopID=:id";
                $stmt1 = $this->conn->prepare($query);
                $stmt1->bindparam(":id",$ShopID);
                $stmt1->execute();
                $row = $stmt1->fetch(PDO::FETCH_ASSOC);
                extract($row);
                $Subject = "Order Delivered.";
                $Message = "<p>Order Has Been Deliever To the Customer.</p>";
                $Message .= "<p> <b>Order No: </b> " . $this->OrderDetailsID . "</p>";
                $mail->send($Email,$Subject,$Message);

                return '{"key":"true"}';
            }
                
            return '{"key":"false"}';
        }else{
            return '{"key":"noaccess"}';
        }
    }

    function OrderShipped(){
        $datetime = date('Y-m-d H:i:s');
        $query = "SELECT *,od.Price FPrice FROM tblorderdetails od
                    LEFT JOIN tblorders as o on od.OrderiD = o.OrderID
                    LEFT JOIN tblproduct as p on od.ProductID = p.ProductID
                    WHERE OrderDetailID=:OrderDetailID AND Status='Confirmed'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":OrderDetailID", $this->OrderDetailsID);
        $stmt->execute();
        $num = $stmt->rowcount();
        $userData = $stmt ->fetch(PDO::FETCH_ASSOC);
        $UserID = $userData['UserID'];
        if($num == 1){
            $status = "Shipped";
            $query = "UPDATE tblorderdetails SET OrderUpdatedOn=:OrderUpdatedOn, status=:status WHERE OrderDetailID=:OrderDetailID";
            $stmt = $this->conn->prepare($query);
            $stmt->bindparam(":status", $status);
            $stmt->bindparam(":OrderUpdatedOn", $datetime);
            $stmt->bindparam(":OrderDetailID", $this->OrderDetailsID);

            if($stmt->execute()){
                $text = "Your Order has Been Shipped for your Address.";
                $query = "INSERT INTO tbltracking(OrderDetailsID,TrackingText,ArrivedTime,DispatchedTime,Status) 
                        values(:OrderDetailsID,:TrackingText,:ArrivedTime,:DispatchedTime,:Status)";
                $stmt = $this->conn->prepare($query);
                $stmt->bindparam(":OrderDetailsID",$this->OrderDetailsID);
                $stmt->bindparam(":TrackingText",$text);
                $stmt->bindparam(":ArrivedTime",$datetime);
                $stmt->bindparam(":DispatchedTime",$datetime);
                $stmt->bindparam(":Status",$status);
                $stmt->execute();

                $mail = new SendMail();
                    $query = "SELECT * FROM tbluser WHERE UserID=:id";
                    $stmt1 = $this->conn->prepare($query);
                    $stmt1->bindparam(":id",$UserID);
                    $stmt1->execute();
                    $row = $stmt1->fetch(PDO::FETCH_ASSOC);
                    extract($row);
                    $Subject = "Order Dispatched.";
                    $Message = "<p>Your Order Has Been Dispatched.</p>";
                    $Message .= "<p>" . $userData['ProductName']  . " ( " . $userData['FPrice'] ." ) * "  . $userData['Qty'] . "</p>";
                    $Message .= "<p><a href='http://localhost:4200/orderDetail?ODID='" . $this->OrderDetailsID . "> Click Here To Track</a></p>";
                    $Message .= "<p> <b>Order No: </b> " . $this->OrderDetailsID . "</p>";
                    $mail->send($Email,$Subject,$Message);

                return '{"key":"true"}';
            }
                
            return '{"key":"false"}';
        }else{
            return '{"key":"noaccess"}';
        }

        
    }

    function OrderCancle(){
        $status = "Cancelled";
        $datetime = date('Y-m-d H:i:s');
            $query = "UPDATE tblorderdetails SET OrderUpdatedOn=:OrderUpdatedOn, status=:status WHERE OrderDetailID=:OrderDetailID";
            $stmt = $this->conn->prepare($query);
            $stmt->bindparam(":status", $status);
            $stmt->bindparam(":OrderUpdatedOn", $datetime);
            $stmt->bindparam(":OrderDetailID", $this->OrderDetailsID);

            if($stmt->execute()){
                $text = "Order Cancelled.";
                $query = "INSERT INTO tbltracking(OrderDetailsID,TrackingText,ArrivedTime,DispatchedTime,Status) 
                        values(:OrderDetailsID,:TrackingText,:ArrivedTime,:DispatchedTime,:Status)";
                $stmt = $this->conn->prepare($query);
                $stmt->bindparam(":OrderDetailsID",$this->OrderDetailsID);
                $stmt->bindparam(":TrackingText",$text);
                $stmt->bindparam(":ArrivedTime",$datetime);
                $stmt->bindparam(":DispatchedTime",$datetime);
                $stmt->bindparam(":Status",$status);

                $stmt->execute();

                $query = "SELECT *,od.Price FPrice FROM tblorderdetails od
                            LEFT JOIN tblorders as o on od.OrderiD = o.OrderID
                            LEFT JOIN tblproduct as p on od.ProductID = p.ProductID
                            WHERE OrderDetailID=:OrderDetailID;";
                $stmt = $this->conn->prepare($query);
                $stmt->bindparam(":OrderDetailID", $this->OrderDetailsID);
                $stmt->execute();
                $userData = $stmt ->fetch(PDO::FETCH_ASSOC);
                $UserID = $userData['UserID'];
                $ShopID = $userData['ShopID'];

                $mail = new SendMail();
                $query = "SELECT * FROM tbluser WHERE UserID=:id";
                $stmt1 = $this->conn->prepare($query);
                $stmt1->bindparam(":id",$UserID);
                $stmt1->execute();
                $row = $stmt1->fetch(PDO::FETCH_ASSOC);
                extract($row);
                $Subject = "Order Has been Cancelled.";
                $Message = "<p>Your Order Has Been Cancelled.</p>";
                
                $Message .= "<p> <b>Order No: </b> " . $this->OrderDetailsID . "</p>";
                $mail->send($Email,$Subject,$Message);

                $query = "SELECT * FROM tblshops WHERE ShopID=:id";
                $stmt1 = $this->conn->prepare($query);
                $stmt1->bindparam(":id",$ShopID);
                $stmt1->execute();
                $row = $stmt1->fetch(PDO::FETCH_ASSOC);
                extract($row);
                $Message = "<p>Order Has Been Cancelled By the Customer.</p>";
                $Message .= "<p>" . $userData['ProductName']  . " ( " . $userData['FPrice'] ." ) * "  . $userData['Qty'] . "</p>";
                $Message .= "<p> <b>Order No: </b> " . $this->OrderDetailsID . "</p>";
                $mail->send($Email,$Subject,$Message);

                $Notification = new Notification($this->conn);
                $Notification->URL = "/tracking";
                $Notification->Type = "0";
                $Notification->Image = "fa-ban";
                $Notification->IsRead = "0";
                $Notification->NotificationText = "Order No " . $this->OrderDetailsID . " Cancelled.";
                $Notification->CreatedOn = date('Y-m-d H:i:s');
                $Notification->AddNotification();
                return '{"key":"true"}';
            }
                
            return '{"key":"false"}';
    }

    function Allorder(){
        $query = "SELECT *,od.ProductID PID,o.UserID CID,od.Price FPrice FROM tblorderdetails od
                    LEFT JOIN tblproduct as p on od.ProductID = p.ProductID
                    LEFT JOIN tblcategory as c on p.CategoryID = c.CategoryID
                    LEFT JOIN tblorders as o on od.OrderID = o.OrderID
                    LEFT JOIN tbluser as u on o.UserID = u.UserID
                    LEFT JOIN tbladdress as a on o.AddressID = a.AddressID
                    LEFT JOIN tblshops as s on p.ShopID = s.ShopID
                    ORDER BY OrderDetailID DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function GetUserOrders($id){
        $query = "SELECT *,od.ProductID PID,o.UserID CID,od.Price PurchasedPrice FROM tblorderdetails od
                    LEFT JOIN tblproduct as p on od.ProductID = p.ProductID
                    LEFT JOIN tblcategory as c on p.CategoryID = c.CategoryID
                    LEFT JOIN tblorders as o on od.OrderID = o.OrderID
                    LEFT JOIN tbladdress as a on o.AddressID = a.AddressID
                    LEFT JOIN tblshops as s on p.ShopID = s.ShopID
                    WHERE o.UserID=:id
                    ORDER BY OrderDetailID DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":id",$id);
        $stmt->execute();
        return $stmt;
    }

    function SigleOrderDetail($id){
        $query = "SELECT *,od.ProductID PID,o.UserID CID,od.Price PurchasedPrice FROM tblorderdetails od
                    LEFT JOIN tblproduct as p on od.ProductID = p.ProductID
                    LEFT JOIN tblcategory as c on p.CategoryID = c.CategoryID
                    LEFT JOIN tblorders as o on od.OrderID = o.OrderID
                    LEFT JOIN tbladdress as a on o.AddressID = a.AddressID
                    LEFT JOIN tblshops as s on p.ShopID = s.ShopID
                    WHERE od.OrderDetailID=:id
                    ORDER BY OrderDetailID DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":id",$id);
        $stmt->execute();
        return $stmt;
    }

    
}
?>