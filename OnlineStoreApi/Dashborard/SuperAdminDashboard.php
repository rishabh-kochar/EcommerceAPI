<?php

include_once '../config/database.php';
include_once './Dashborad.php';

$database = new Database();
$db = $database->getConnection();
 
$Dashboard = new Dashboard($db);

$res = $Dashboard->SuperAdminDashboard();
echo json_encode($res);

?>