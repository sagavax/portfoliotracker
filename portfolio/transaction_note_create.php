<?php

    include("../includes/dbconnect.php");
    include("../includes/functions.php");


    $transaction_id = $_POST['transaction_id'];
    $note_text = $_POST['note_text'];

    $create_note = "INSERT INTO transaction_notes (transaction_id, note_text) VALUES ('$transaction_id', '$note_text')";
    $result = mysqli_query($conn, $create_note);

    $latest_note_id = mysqli_insert_id($conn);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error creating note'], 'noteId'=> $latest_note_id);
    }
