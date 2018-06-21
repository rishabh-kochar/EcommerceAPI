<?php

include_once '../config/database.php';
include_once './Cart.php';

$database = new Database();
$db = $database->getConnection();
 
$Cart = new Cart($db);

$id = $_GET['id'];
$res = $Cart->DeleteFromCart($id);
if($res)
    echo '{"key":"true"}';
else
    echo '{"key":"false"}';

?>