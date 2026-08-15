<?php

    include("../includes/dbconnect.php");
    include("../includes/functions.php");


    $input = json_decode(file_get_contents('php://input'), true);
    $note_id = mysqli_real_escape_string($link, $input['note_id'] ?? '');

    $delete_note = "DELETE FROM transaction_notes WHERE id = '$note_id'";
    $result = mysqli_query($link, $delete_note) or die(mysqli_error($link));

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Note deleted successfully', 'note_id' => $note_id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting note']);
    }