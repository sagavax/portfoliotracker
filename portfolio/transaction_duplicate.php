<?php

    include('../includes/dbconnect.php');
    include('../includes/functions.php');


    $transaction_id = $_POST['transaction_id'];
    
    //duplicate transaction
    $duplicate_transaction_query = "INSERT INTO transactions (symbol, transaction_date, provider, asset_category, currency, quantity, entry_price, position_type, spot_perpetual, manual_bot) SELECT symbol, transaction_date, provider, asset_category, currency, quantity, entry_price, position_type, spot_perpetual, manual_bot FROM transactions WHERE id = $transaction_id";
    $duplicate_transaction_result = mysqli_query($link, $duplicate_transaction_query) or die(mysqli_error($link));

    //get the last inserted transaction id
    $new_transaction_id = mysqli_insert_id($link);

    if ($duplicate_transaction_result) {
        echo json_encode(['success' => true, 'message' => 'Transaction duplicated successfully.', 'transaction_id' => $new_transaction_id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error duplicating transaction: ' . mysqli_error($link)]);
    }