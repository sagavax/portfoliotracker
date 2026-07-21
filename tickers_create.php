<?php

include('includes/dbconnect.php');
include('includes/functions.php');


$new_ticker = mysqli_real_escape_string($link, $_POST['symbol']) ?? trim($_POST['symbol']);
$short_name = mysqli_real_escape_string($link, $_POST['short_name']) ?? trim($_POST['short_name']);
$industry = mysqli_real_escape_string($link, $_POST['industry']) ?? trim($_POST['industry']);
$website = mysqli_real_escape_string($link, $_POST['website']) ?? trim($_POST['website']);

$create_ticker = "INSERT INTO tickers (ticker, short_name, industry, website) VALUES ('$new_ticker', '$short_name', '$industry', '$website')";
$result = mysqli_query($link, $create_ticker) or die("MySQLi ERROR: ".mysqli_error($link));

//get the last inserted ticker id
$last_inserted_id = mysqli_insert_id($link);    

echo json_encode(['success' => true, 'message' => 'Ticker created successfully.', 'ticker_id' => $last_inserted_id]);