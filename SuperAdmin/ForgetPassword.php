<?php

include_once '../config/database.php';
include_once 'SuperAdmin.php';

$database = new Database();
$db = $database->getConnection();
 
$SuperAdmin = new SuperAdmin($db);
//$SuperAdmin->ReadInfo();
$SuperAdmin->ForgetPassword(1,"9016111959");

?>