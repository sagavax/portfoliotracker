<?php

    include("includes/dbconnect.php");
    include("includes/functions.php");



    $get_latewst_transaction = "SELECT * FROM transactions ORDER BY created_at DESC LIMIT 1";
    $result = mysqli_query($link, $get_latewst_transaction) or die("MySQLi ERROR: ".mysqli_error($link));
    $row = mysqli_fetch_array($result);
    $transaction_id = $row['id'];

    echo $transaction_id;