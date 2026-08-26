<?php

include('../../includes/dbconnect.php');
include('../../includes/functions.php');

//default /GET request to fetch all transactions
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    include('transactions_get.php');
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //check if the request is to update a transaction
    if (isset($_POST['transaction_id'])) {
        include('transactions_update.php');
    } elseif (isset($_POST['delete_transaction_id'])) {
        include('transactions_delete.php');
    } else {
        die("No valid parameters provided for POST request.");
    }
} else {
    die("Invalid request method.");
}


?>