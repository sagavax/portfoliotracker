<?php

    include('../includes/dbconnect.php');
    include('../includes/functions.php');


    $transaction_id = $_POST['transaction_id'];
    $quantity = $_POST['quantity'];


    $update_quantity = "UPDATE transactions SET quantity='$quantity' WHERE id='$transaction_id'";
    $result = mysqli_query($link, $update_quantity) or die(mysqli_error($link));

    echo json_encode(["status" => "success", "message" => "Transaction entry quantity updated successfully"]);