<?php

    include("../includes/dbconnect.php");
    include("../includes/functions.php");


    $input = json_decode(file_get_contents('php://input'), true);

    $transaction_id = mysqli_real_escape_string($link, $input['transaction_id'] ?? '');
    $note_text = mysqli_real_escape_string($link, $input['note_text'] ?? '');

    $create_note = "INSERT INTO transaction_notes (transaction_id, note_text) VALUES ('$transaction_id', '$note_text')";
    $result = mysqli_query($link, $create_note);

    $latest_note_id = mysqli_insert_id($link);
    $created_at = date('Y-m-d H:i:s');

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Note created successfully', 'note_id' => $latest_note_id, 'created_at' => $created_at]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error creating note']);
    }
