<?php

    include("includes/dbconnect.php");
    include("includes/functions.php");


    $transaction_id = $_POST['transaction_id'];
    $category = $_POST['category'];


    $update_category = "UPDATE transactions SET category = '$category' WHERE id = $transaction_id";
    $result = mysqli_query($link, $update_category) or die("MySQLi ERROR: ".mysqli_error($link));

    echo json_encode(["status" => "success", "message" => "Category updated successfully"]);