<?php

include('../includes/dbconnect.php');
include('../includes/functions.php');

$transaction_id = $_POST['transaction_id'];
$spot_perpetual = $_POST['spot_perpetual'];

$update_transaction_spot_perpetual = "UPDATE transactions SET spot_perpetual = '$spot_perpetual' WHERE id = '$transaction_id'";
$result = mysqli_query($link, $update_transaction_spot_perpetual) or die("MySQLi ERROR: ".mysqli_error($link)); 

echo json_encode(['status' => 'success', 'message' => 'Transaction updated successfully.']);    

?>