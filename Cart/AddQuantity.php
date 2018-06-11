<?php

include_once '../config/database.php';
include_once './Cart.php';

$database = new Database();
$db = $database->getConnection();
 
$Cart = new Cart($db);

$data = json_decode(file_get_contents("php://input"));

$id = $data->ID;
$qty = $data->qty;

$res = $Cart->AddQty($id,$qty);
if($res)
    echo '{"key":"true"}';
else
    echo '{"key":"false"}';

?>