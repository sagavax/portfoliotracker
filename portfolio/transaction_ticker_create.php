<?php

    include('../includes/dbconnect.php');
    include('../includes/functions.php');

    $symbol = $_POST['symbol'];
    

    //create new ticker
    $sql = "INSERT INTO tickers (symbol) VALUES ('$symbol')";

    if (mysqli_query($link, $sql)) {
        echo json_encode(array("success" => true, "message" => "New ticker created successfully"));
    } else {
        echo json_encode(array("success" => false, "message" => "Error creating new ticker"));
    }