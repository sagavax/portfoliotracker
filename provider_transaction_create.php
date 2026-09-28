<?php

    include_once 'includes/dbconnect.php';
    include_once 'includes/functions.php';

    $provider_name = mysqli_real_escape_string($link, $_POST['provider_name'] ?? '');

    if (empty($provider_name)) {
        echo json_encode(['status' => 'error', 'message' => 'Provider is required.']);
        exit;
    }

    $create_transaction = "INSERT INTO transactions (date_of_transaction, provider, position_type, spot_perpetual, manual_bot, created_at) VALUES (CURDATE(), '$provider_name', '', '', '', NOW())";
    $result = mysqli_query($link, $create_transaction) or die("MySQLi ERROR: " . mysqli_error($link));

    $transaction_id = mysqli_insert_id($link);

    echo json_encode(['status' => 'success', 'transaction_id' => $transaction_id]);
