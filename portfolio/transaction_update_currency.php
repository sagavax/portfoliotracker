<?php

include('../includes/dbconnect.php');
include('../includes/functions.php');

$transaction_id = $_POST['transaction_id'];
$currency = $_POST['currency'];

$update_currency = "UPDATE transactions SET currency='$currency' WHERE id='$transaction_id'";
 $result = mysqli_query($link, $update_currency) or die(mysqli_error($link));

echo json_encode(["status" => "success", "message" => "Transaction currency updated successfully"]);