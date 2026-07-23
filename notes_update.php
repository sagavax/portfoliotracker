<?php

    include('includes/dbconnect.php');
    include('includes/functions.php');


    $note_id = (int)$_POST['noteId'];
    $note_text = mysqli_real_escape_string($link, $_POST['noteText']);

    $update_note_sql = "UPDATE transaction_notes SET note_text = '".$note_text."', modified_at = NOW() WHERE id = $note_id";
    $result = mysqli_query($link, $update_note_sql) or die("MySQLi ERROR: ".mysqli_error($link));

    echo json_encode(['success' => true, 'message' => 'Note updated successfully.', 'note_id' => $note_id]);