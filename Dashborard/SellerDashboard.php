<?php

include_once '../config/database.php';
include_once './Dashborad.php';


$database = new Database();
$db = $database->getConnection();
 
$Dashboard = new Dashboard($db);
$id = $_GET['id'];

$res = $Dashboard->SellerDashboard($id);
echo json_encode($res);

?>