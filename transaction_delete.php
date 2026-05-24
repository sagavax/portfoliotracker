<?php

    include("includes/dbconnect.php");
    include("includes/functions.php");

    $transaction_id = $_POST['transaction_id'];

    $delete_transaction = "DELETE FROM transactions WHERE id = $transaction_id";
    $result = mysqli_query($link, $delete_transaction) or die("MySQLi ERROR: ".mysqli_error($link));

    echo json_encode(["status" => "success", "message" => "Transaction Deleted Successfully"]);