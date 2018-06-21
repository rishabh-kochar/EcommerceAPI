<?php

include_once '../config/database.php';
include_once './Dashborad.php';

$database = new Database();
$db = $database->getConnection();
 
$Dashboard = new Dashboard($db);

$id = $_GET['id'];
$stmt = $Dashboard->TotalOrderValue($id);
echo '{"Amount":"' . $stmt . '"}';


?>