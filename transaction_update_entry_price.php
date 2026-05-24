<?php

    include("includes/dbconnect.php");
    include("includes/functions.php");


    $transaction_id = $_POST['transaction_id'];
    $entry_price = $_POST['entry_price'];


    $update_entry_price = "UPDATE transactions SET entry_price='$entry_price' WHERE id='$transaction_id'";
    $result = mysqli_query($link, $update_entry_price) or die(mysqli_error($link));

    echo json_encode(["status" => "success", "message" => "Transaction entry price updated successfully"]);