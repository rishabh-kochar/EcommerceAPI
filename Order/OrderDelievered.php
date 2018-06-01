<?php

include_once '../config/database.php';
include_once './Orders.php';

$database = new Database();
$db = $database->getConnection();
 
$Order = new Order($db);

$Order->OrderDetailsID = $_GET['id'];

if($Order->OrderDelievered())
    echo '{"key":"true"}';
else
    echo '{"key":"false"}';

?>