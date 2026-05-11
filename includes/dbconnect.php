<?php
// Development settings - show all errors

// Database connection configuration
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "root";
$dbname = "portfolio_prod";

$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$link) {
    die("Database connection failed: " . mysqli_connect_error());
}