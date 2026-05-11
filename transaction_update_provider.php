<?php
    include("includes/dbconnect.php");
    include("includes/functions.php");

    $transaction_id = $_POST['transaction_id'];
    $provider = $_POST['provider'];


    $update_provider = "UPDATE transactions SET provider = '$provider' WHERE id = $transaction_id";
    $result = mysqli_query($link, $update_provider) or die("MySQLi ERROR: ".mysqli_error($link));

    echo json_encode(["status" => "success", "message" => "Provider updated successfully"]);