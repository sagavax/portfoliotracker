<?php
$dbhost = "localhost";
$dbuser = "your_username";
$dbpass = "your_password";
$dbname = "your_database";

$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$link) {
    die("Database connection failed: " . mysqli_connect_error());
}
