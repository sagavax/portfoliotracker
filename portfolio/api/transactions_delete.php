<?php
    include('../../includes/dbconnect.php');
    include('../../includes/functions.php');

    $transaction_id = $_POST['transaction_id'];

    $remove_transaction = "UPDATE transactions SET is_closed = 1,modified_at = NOW() WHERE id = $transaction_id";
    $result = mysqli_query($link, $remove_transaction) or die("MySQLi ERROR: ".mysqli_error($link));