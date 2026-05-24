<?php

    include("includes/dbconnect.php");
    include("includes/functions.php");

    $transaction_id = $_POST['transaction_id'];
    $note = mysqli_real_escape_string($link, $_POST['note']);

    $update_note = "UPDATE transaction_notes SET note_text='$note' WHERE id='$transaction_id'";
    $result = mysqli_query($link, $update_note) or die(mysqli_error($link));

    echo json_encode(["status" => "success", "message" => "Transaction note updated successfully"]);
