<?php

include('../includes/dbconnect.php');
include('../includes/functions.php');

$currentTransactionId = $_POST['transaction_id'] ?? null;
$takeProfit = $_POST['take_profit'] ?? null;

$updateTakeProfit = "UPDATE transactions SET tp_price = '$takeProfit' WHERE id = $currentTransactionId";
$result = mysqli_query($link, $updateTakeProfit) or die("MySQLi ERROR: ".mysqli_error($link));

echo json_encode(["status" => "success", "message" => "Take profit updated successfully"]);

?>
