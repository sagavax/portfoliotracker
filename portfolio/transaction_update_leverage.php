<?php

    include("../includes/dbconnect.php");
    include("../includes/functions.php");


    $transaction_id = $_POST['transaction_id'];
    $leverage = $_POST['leverage'];


    $update_leverage_query = "UPDATE transactions SET leverage = '$leverage' WHERE id = $transaction_id";
    $result = mysqli_query($link, $update_leverage_query) or die("MySQL ERROR: " . mysqli_error($link));

    echo json_encode(array("success" => true, "message" => "Leverage updated successfully.", "transaction_id" => $transaction_id, "leverage" => $leverage));