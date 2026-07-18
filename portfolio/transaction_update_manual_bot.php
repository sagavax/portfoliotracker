<?php

    include('../includes/dbconnect.php');
    include('../includes/functions.php');

    $transaction_id = $_POST['transaction_id'];  
    $manualbot = $_POST['manual_bot'];

    $update_manual_bot = "UPDATE transactions SET manual_bot = '$manualbot', modified_at = NOW() WHERE transaction_id = $transaction_id";
    $result = mysqli_query($link, $update_manual_bot) or die(mysqli_error($link));

    echo json_encode(["status" => "success", "message" => "Manual bot updated successfully"]);