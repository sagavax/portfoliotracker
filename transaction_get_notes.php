<?php

    include('includes/dbconnect.php');
    include('includes/functions.php');

    $transaction_id = $_POST['transaction_id'];

    $get_transation_notes = "SELECT * FROM transaction_notes WHERE transaction_id = $transaction_id";
    $result = mysqli_query($link, $get_transation_notes) or die("MySQLi ERROR: ".mysqli_error($link));

    echo "<div class='notes_header_wrapper'><button type='button' class='transaction_button' name='add_note'><i class='fa fa-plus'></i> Add note</button><button type='button' class='transaction_button' name='save_note'><i class='fa fa-pencil'></i> Save note</button><button type='button' class='transaction_button' name='close_notes'><i class='fa fa-times'></i></button></div>";

    echo "<div class='notes_wrapper'>";
     while($row = mysqli_fetch_assoc($result)) {
        echo "<div class='note' data-note-id='" . $row['id'] . "'>";
        echo "<p>" . $row['note_text'] . "</p>";
        echo "</div>";
       }
     
    echo "</div>";
       
       //echo json_encode(["status" => "success", "message" => "Transaction notes loaded successfully"]); 
    ?>