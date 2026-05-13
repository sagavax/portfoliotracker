<?php
    include("includes/dbconnect.php");
    include("includes/functions.php");

    $transaction_id = $_POST['transaction_id'];
    $long_short = $_POST['long_short'];

    $update_long_short = "UPDATE transactions SET type = '$long_short' WHERE id = $transaction_id";
    $result = mysqli_query($link, $update_long_short) or die("MySQLi ERROR: ".mysqli_error($link));

    echo json_encode(["status" => "success", "message" => "Transaction type updated successfully"]);
