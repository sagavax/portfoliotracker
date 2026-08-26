<?php
    include_once('../../includes/dbconnect.php');
    include_once('../../includes/functions.php');

    $transaction_id = $_POST['transaction_id'];
    if(isset($_POST['entry_price'])) {
        $entry_price = $_POST['entry_price'];
        $update_transaction = "UPDATE transactions SET entry_price = $entry_price, modified_at = NOW() WHERE id = $transaction_id";
    } elseif(isset($_POST['tp_price'])) {
        $tp_price = $_POST['tp_price'];
        $update_transaction = "UPDATE transactions SET tp_price = $tp_price, modified_at = NOW() WHERE id = $transaction_id";
    } elseif(isset($_POST['sl_price'])) {
        $sl_price = $_POST['sl_price'];
        $update_transaction = "UPDATE transactions SET sl_price = $sl_price, modified_at = NOW() WHERE id = $transaction_id";
    } elseif(isset($_POST['quantity'])) {
        $quantity = $_POST['quantity'];
        $update_transaction = "UPDATE transactions SET quantity = $quantity, modified_at = NOW() WHERE id = $transaction_id";
    } else {
        die("No valid parameters provided for update.");
    }

    $result = mysqli_query($link, $update_transaction) or die("MySQLi ERROR: " . mysqli_error($link));


    ?>