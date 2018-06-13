<?php

include_once '../config/database.php';
include_once './Orders.php';

$database = new Database();
$db = $database->getConnection();
 
$Order = new Order($db);

$Order->OrderDetailsID = $_GET['id'];

$res=$Order->OrderDelievered();
echo $res;
?>