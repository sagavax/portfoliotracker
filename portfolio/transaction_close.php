<?php

    include('../includes/dbconnect.php');
    include('../includes/functions.php');


    $transactionId  = $_POST['transaction_id'] ?? null;


    $closeTransaction = "UPDATE transactions set is_closed = 1 where id = $transactionId";
    echo $closeTransaction;
    mysqli_query($link, $closeTransaction) or die("Error closing transaction: " . mysqli_error($link));

    echo json_encode(['success' => true, 'message' => 'Transaction closed successfully']);