<?php

    include('../includes/dbconnect.php');
    include('../includes/functions.php');

    $transaction_id = $_POST['transaction_id'];    
    $note = mysqli_real_escape_string($link, $_POST['note']);

    $update_note = "INSERT INTO transaction_notes (transaction_id, note_text, modified_at)  VALUES ($transaction_id, '$note', NOW())";
    $result = mysqli_query($link, $update_note) or die(mysqli_error($link));

    echo json_encode(["status" => "success", "message" => "Transaction note created successfully"]);
