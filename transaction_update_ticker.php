<?php

    include("includes/dbconnect.php");
    include("includes/functions.php");


    $transaction_id = $_POST['transaction_id'];
    $ticker = $_POST['ticker'];

    $update_ticker = "UPDATE transactions SET symbol = '$ticker' WHERE id = $transaction_id";
    $result = mysqli_query($link, $update_ticker) or die("MySQLi ERROR: ".mysqli_error($link));

    echo json_encode(["status" => "success", "message" => "Ticker updated successfully"]);