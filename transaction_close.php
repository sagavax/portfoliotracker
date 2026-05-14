<?php

    include("includes/dbconnect.php");
    include("includes/functions.php");


    $transactionId  = $_POST['transactionId'] ?? null;


    $closeTransaction = "update transactions set is_closed = 1 where id = $transactionId";
    mysqli_query($link, $closeTransaction) or die("Error closing transaction: " . mysqli_error($link));