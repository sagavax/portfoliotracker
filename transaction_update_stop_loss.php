<?php

    include('includes/dbconnect.php');
    include('includes/functions.php');


    $currentTransactionId = $_POST['transaction_id'] ?? null;
    $stopLoss = $_POST['stop_loss'] ?? null;

    $update_stop_loss = "UPDATE transactions SET sl_price = '$stopLoss' WHERE id = $currentTransactionId";
    $result = mysqli_query($link, $update_stop_loss) or die("MySQLi ERROR: ".mysqli_error($link));

    echo json_encode(["status" => "success", "message" => "Stop loss updated successfully"]);

?>
