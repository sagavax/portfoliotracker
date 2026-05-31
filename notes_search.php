<?php

    include('includes/dbconnect.php');
    include('includes/functions.php');

   $search_value = $_POST['search_value'];

    $searchNotes = "SELECT * FROM transaction_notes WHERE note_text LIKE '%$search_value%'";
    $result = mysqli_query($link, $searchNotes) or die("MySQLi ERROR: ".mysqli_error($link));

    if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            echo "<div class='note' data-note-id='" . $row['id'] . "'>";
            echo "<p>" . $row['note_text'] . "</p>";
            echo "<small>Created: " . $row['created_at'] . "</small>";
            echo "<small>Modified: " . $row['modified_at'] . "</small>";
            echo "</div>";
        }
    } else {
        echo "<p>No notes found</p>";
    }