<?php
include "connect.php";

$ipaddress =filter_input(INPUT_GET, "ipaddress" , FILTER_DEFAULT);
$address =filter_input(INPUT_GET, "addr" , FILTER_DEFAULT);
$amount =filter_input(INPUT_GET, "amount" , FILTER_DEFAULT);
$hashV =filter_input(INPUT_GET, "hashV" , FILTER_DEFAULT);


 
    $cmd = "Insert into `Recents`(`Address`, `Amount`,`ipaddress`,`Time` ,`hashValue`) values (?,?,?,?,?)";
    $stmt = $dbh->prepare($cmd);

    $d=strtotime("today");
    $param = [$ipaddress,$address, $amount ,date("Y-m-d h:i:sa", $d),$hashV];
    $success = $stmt->execute($param);

    echo json_encode(1);

?>