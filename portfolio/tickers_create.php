<?php

include('includes/dbconnect.php');
include('includes/functions.php');


$new_ticker = mysqli_real_escape_string($link, $_POST['new_ticker']) ?? trim($_POST['new_ticker']);

$create_ticker = "INSERT INTO tickers (ticker) VALUES ('$new_ticker')";
$result = mysqli_query($link, $create_ticker) or die("MySQLi ERROR: ".mysqli_error($link));

//get the last inserted ticker id
$last_inserted_id = mysqli_insert_id($link);    

echo json_encode(['success' => true, 'message' => 'Ticker created successfully.', 'ticker_id' => $last_inserted_id]);