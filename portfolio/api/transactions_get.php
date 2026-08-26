<?php
include_once('../../includes/dbconnect.php');
include_once('../../includes/functions.php');

header('Content-Type: application/json');

$get_transations = "SELECT * FROM transactions WHERE is_closed = 0 ORDER BY created_at DESC";
$result = mysqli_query($link, $get_transations) or die("MySQL ERROR: " . mysqli_error($link));

$response_data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $transaction_id = $row['id'];

    $response_data[] = [
        'id'              => $transaction_id,
        'ticker'          => $row['symbol'],
        'provider'        => $row['provider'],
        'category'        => $row['asset_category'],
        'currency'        => $row['currency'],
        'leverage'        => $row['leverage'],
        'quantity'        => $row['quantity'],
        'entry_price'     => $row['entry_price'],
        'tp_price'        => $row['tp_price'],
        'sl_price'        => $row['sl_price'],
        'position_type'   => $row['position_type'],
        'created_at'      => $row['created_at'],
        'spot_perpetual'  => $row['spot_perpetual'],
        'manual_bot'      => $row['manual_bot'],
        'notes_count'     => (int) GetCountTransactionNotes($transaction_id),
    ];
}

echo json_encode($response_data);
