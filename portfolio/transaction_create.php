<?php 

    include('../includes/dbconnect.php');
    include('../includes/functions.php');


    //create new transaction

    $create_transaction = "INSERT INTO transactions (date_of_transaction,created_at) VALUES (CURDATE(),now())";
    $result = mysqli_query($link, $create_transaction) or die("MySQLi ERROR: ".mysqli_error($link));

