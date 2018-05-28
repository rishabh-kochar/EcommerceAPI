<?php

include_once './database.php';

$database = new Database();
$db = $database->getConnection();

$conn = $db;


$Id = $_GET['Id'];
$userType = $_GET['type'];
$status = $_GET['status'];
$operation = $_GET['operation'];

if($userType == "superadmin"){
    if($operation == "get"){
        $query = "SELECT * FROM tbladmin WHERE Email = '" . $Id . "' OR PhoneNo = '" . $Id . "'";

    }elseif($operation == "set"){
        if($status == "1")
            $query = "UPDATE tbladmin SET IsSessionActive = 1 WHERE Email = '" . $Id . "' OR PhoneNo = '" . $Id . "'";
        elseif($status == "0")
            $query = "UPDATE tbladmin SET IsSessionActive = 0 WHERE Email = '" . $Id . "' OR PhoneNo = '" . $Id . "'";
    }
}elseif($userType == "seller"){
    if($operation == "get"){
        $query = "SELECT * FROM tblshops WHERE UserName = '" . $Id ."'";
    }elseif($operation == "set"){
        if($status == "1")
            $query = "UPDATE tblshops SET IsSessionActive = 1 WHERE UserName = '" . $Id ."' Email = '" . $Id . "' OR PhoneNo = '" . $Id . "'";
        elseif($status == "0")
            $query = "UPDATE tblshops SET IsSessionActive = 0 WHERE UserName = '" . $Id ."' Email = '" . $Id . "' OR PhoneNo = '" . $Id . "'";
    }
}

//echo $query;
$stmt = $conn->prepare($query);
$stmt->execute();
$num = $stmt->rowCount();

if($num>0){
   
    if($userType == "superadmin"){
        if($operation == "get"){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($row);
            if($IsSessionActive){
                
                $SuperAdmin_arr=array();
                // retrieve our table contents
                $SuperAdmin_arr=array(
                    "key" => "true",
                    "Adminid" => $AdminId,
                    "Adminname" => $AdminName,
                    "AdminImage" => $AdminImage,
                    "PhoneNo" => $PhoneNo,
                    "Email" => $Email,
                    "Password" => $Password,
                    "OldPassword" => $OldPassword,
                    "CreatedOn" => $CreatedOn,
                    "PasswordUpdatedOn" => $PasswordUpdatedOn
                );
                    
                echo json_encode($SuperAdmin_arr);
            }else{
                echo '{ "key" : "false" }';
            }
        }else{
            if($status == "1"){
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($row);
                $SuperAdmin_arr=array();
                
                $SuperAdmin_arr=array(
                    "key" => "true",
                    "Adminid" => $AdminId,
                    "Adminname" => $AdminName,
                    "AdminImage" => $AdminImage,
                    "PhoneNo" => $PhoneNo,
                    "Email" => $Email,
                    "Password" => $Password,
                    "OldPassword" => $OldPassword,
                    "CreatedOn" => $CreatedOn,
                    "PasswordUpdatedOn" => $PasswordUpdatedOn
                );
                    
                echo json_encode($SuperAdmin_arr);
            }elseif($status == "0"){
                echo '{ "key" : "true" }';
            }
            
        }
    }else{
        if($operation == "get"){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($row);
            if($IsSessionActive){
                
                $SuperAdmin_arr=array();
                // retrieve our table contents
                $SuperAdmin_arr=array(
                    "key" => "true",
                    "ShopID" => $ShopID,
                    "ShopName" => $ShopName,
                    "Tagline" => $Tagline,
                    "LogoImage" => $LogoImage,
                    "Address" => $Address,
                    "City" => $City,
                    "State" => $State,
                    "PhoneNo" => $PhoneNo,
                    "Email" => $Email,
                    "Website" => $Website,
                    "OwnerName" => $OwnerName,
                    "FacebookLink" => $FacebookLink,
                    "InstagramLink" => $InstagramLink,
                    "TwitterLink" => $TwitterLink,
                    "YoutubeLink" => $YoutubeLink,
                    "LogoAlt" => $LogoAlt,
                    "GSTNo" => $GSTNo,
                    "UserName" => $UserName,
                    "Password" => $Password,
                    "OldPassword" => $OldPassword,
                    "PasswordUpdatedOn" => $PasswordUpdatedOn,
                    "IsActive" => $IsActive,
                    "CreatedOn" => $CreatedOn,
                    "ShopType" => $ShopType,
                    "ApprovedOn" => $ApprovedOn,
                    "IsSessionActive" => $IsSessionActive,
                    "IsApproved" => $IsApproved
                );
                    
                echo json_encode($SuperAdmin_arr);
            }else{
                echo '{ "key" : "false" }';
            }
            
        }else{
            if($status == "1"){
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($row);
                $SuperAdmin_arr=array();
                
                $SuperAdmin_arr=array(
                    "key" => "true",
                    "ShopID" => $ShopID,
                    "ShopName" => $ShopName,
                    "Tagline" => $Tagline,
                    "LogoImage" => $LogoImage,
                    "Address" => $Address,
                    "City" => $City,
                    "State" => $State,
                    "PhoneNo" => $PhoneNo,
                    "Email" => $Email,
                    "Website" => $Website,
                    "OwnerName" => $OwnerName,
                    "FacebookLink" => $FacebookLink,
                    "InstagramLink" => $InstagramLink,
                    "TwitterLink" => $TwitterLink,
                    "YoutubeLink" => $YoutubeLink,
                    "LogoAlt" => $LogoAlt,
                    "GSTNo" => $GSTNo,
                    "UserName" => $UserName,
                    "Password" => $Password,
                    "OldPassword" => $OldPassword,
                    "PasswordUpdatedOn" => $PasswordUpdatedOn,
                    "IsActive" => $IsActive,
                    "CreatedOn" => $CreatedOn,
                    "ShopType" => $ShopType,
                    "ApprovedOn" => $ApprovedOn,
                    "IsSessionActive" => $IsSessionActive,
                    "IsApproved" => $IsApproved
                );
                    
                echo json_encode($SuperAdmin_arr);
            }elseif($status == "0"){
                echo '{ "key" : "true" }';
            }
            
        }
    }
     
}else{
    echo '{ "key" : "false" }';
}

?>