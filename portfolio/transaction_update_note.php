<?php

    include('../includes/dbconnect.php');
    include('../includes/functions.php');

         $transaction_id = $_POST['transaction_id'];    
    

    $note = mysqli_real_escape_string($link, $_POST['note']);

    $update_note = "INSERT INTO transaction_notes (transaction_id, note_text, modified_at)  VALUES ($transaction_id, '$note', NOW())";
    $result = mysqli_query($link, $update_note) or die(mysqli_error($link));
    
    //get total number of notes for this transaction to display on the button
    $get_count_notes = "SELECT COUNT(*) as note_count FROM transaction_notes WHERE transaction_id = $transaction_id";
    $count_result = mysqli_query($link, $get_count_notes) or die(mysqli_error($link));
    $count_row = mysqli_fetch_assoc($count_result);
    $note_count = $count_row['note_count'];

     echo json_encode(["status" => "success", "message" => "Transaction note created successfully", "note_count" => $note_count]);
     
    //echo json_encode(["status" => "success", "message" => "Transaction note updated successfully"]);
