<?php

include_once './database.php';

$database = new Database();
$db = $database->getConnection();

$conn = $db;

// $data = json_decode(file_get_contents("php://input"));

// $Id = $data->Id;
// $userType = $data->type;
// $Status = $data->status;
// $operation = $data->operation;

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
}elseif($userType == "shop"){
    if($operation == "get"){
        $query = "SELECT * FROM tblshops WHERE UserName = '" . $Id ."'";
    }elseif($operation == "set"){
        if($status == "1")
            $query = "UPDATE tblshops SET IsSessionActive = 1 WHERE UserName = '" . $Id ."'";
        elseif($status == "0")
            $query = "UPDATE tblshops SET IsSessionActive = 0 WHERE UserName = '" . $Id ."'";
    }
}

//echo $query;
$stmt = $conn->prepare($query);
$stmt->execute();
$num = $stmt->rowCount();

if($num>0){
   
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
    echo '{ "key" : "false" }';
}

?>