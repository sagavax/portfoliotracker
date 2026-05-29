<?php

include('includes/dbconnect.php');
include('includes/functions.php');

$filter_name = $_POST['filter_name'];

if($filter_name == "all") {
    $get_transactions = "SELECT * FROM transactions ORDER BY transaction_date DESC";
} else {
    $get_transactions = "SELECT * FROM transactions WHERE asset_category='$filter_name' ORDER BY transaction_date DESC";
} 

if($filter_name == "active") {
    $get_transactions = "SELECT * FROM transactions WHERE is_closed='0' ORDER BY transaction_date DESC";
} elseif ($filter_name == "closed") {
    $get_transactions = "SELECT * FROM transactions WHERE is_closed='1' ORDER BY transaction_date DESC";
}

if($filter_name == "long") {
    $get_transactions = "SELECT * FROM transactions WHERE position_type='long' ORDER BY transaction_date DESC";
} elseif ($filter_name == "short") {
    $get_transactions = "SELECT * FROM transactions WHERE position_type='short' ORDER BY transaction_date DESC";
}


$result = mysqli_query($link, $get_transactions) or die("MySQL ERROR: " . mysqli_error($link));
    while ($row = mysqli_fetch_array($result)) {
        $transaction_id = $row['id'];
        $transaction_ticker = $row['symbol'];
        $transaction_provider = $row['provider'];
        $transaction_category = $row['category'];
        $transaction_currency = $row['currency'];
        $transaction_quantity = $row['quantity'];
        $transaction_entry_price = $row['entry_price'];
        $transaction_tp = $row['tp_price'];
        $transaction_sl = $row['sl_price'];
        $transaction_long_short = $row['position_type'];
        $transaction_created_at = $row['created_at'];
        $transaction_spot_perpetual = $row['spot_perpetual'];
        $transaction_manual_bot = $row['manual_bot'];

        echo "<tr class='transaction' data-id='$transaction_id'>";
            
            if($transaction_ticker) {
                echo "<td><button type='button' class='transaction_button' name='ticker'>$transaction_ticker</button></td>";
            } else {
                echo "<td><button type='button' class='transaction_button' name='ticker'><i class='fa fa-plus'></i> Add ticker</button></td>";
            }
            
            if($transaction_provider) {
                echo "<td><button type='button' class='transaction_button' name='provider'>$transaction_provider</button></td>";
            } else {
                echo "<td><button type='button' class='transaction_button' name='provider'><i class='fa fa-plus'></i> Add provider</button></td>";
            }
            
            
            if($transaction_category) {
                echo "<td><button type='button' class='transaction_button' name='category'>$transaction_category</button></td>";
            } else {
                echo "<td><button type='button' class='transaction_button' name='category'><i class='fa fa-plus'></i> Add category</button></td>";
            }
            
            echo "<td><button type='button' class='transaction_button' name='currency'>$transaction_currency</button>";
            
            if($transaction_quantity != 0) {
                echo "<td><div class='quantity' contenteditable='true'>$transaction_quantity</div></td>";
            } else {
                echo "<td><button type='button' class='transaction_button' name='add_quantity'><i class='fa fa-plus '></i> Add quantity</button></td>";
            }
            
            if($transaction_entry_price != 0.0) {
                echo "<td><div class='price' contenteditable='true'>$transaction_entry_price</div></td>";
            } else {
                echo "<td><button type='button' class='transaction_button' name='add_entry_price'><i class='fa fa-plus'></i> Add price</button></td>";
            }
            
            $tp_display = ($transaction_tp == 0.0) ? "TP" : $transaction_tp;
            $sl_display = ($transaction_sl == 0.0) ? "SL" : $transaction_sl;
            echo "<td style='display: flex; gap: 10px;'><button type='button' class='transaction_button' name='take_profit'>".$tp_display."</button> / <button type='button' class='transaction_button' name='stop_loss'>".$sl_display."</button></td>";
            
            
            if($transaction_long_short) {
                $ls_class = ($transaction_long_short === "BUY") ? "long" : (($transaction_long_short === "SELL") ? "short" : "");
                echo "<td><button type='button' class='transaction_button ".$ls_class."' name='long_short'>".$transaction_long_short."</button></td>";
            } else {
                echo "<td><button type='button' class='transaction_button' name='long_short'><i class='fa fa-plus'></i> Long/Short</button></td>";
            }
            
            if($transaction_spot_perpetual) {
                $sp_class = ($transaction_spot_perpetual === "Spot") ? "green" : (($transaction_spot_perpetual === "Perpetual") ? "blue" : "");
                echo "<td><button type='button' class='transaction_button ".$sp_class."' name='spot_perpetual'>".$transaction_spot_perpetual."</button></td>";
            } else {
                echo "<td><button type='button' class='transaction_button' name='spot_perpetual'><i class='fa fa-plus'></i> Spot / Perpetual</button></td>";
            }
            
            if($transaction_manual_bot) {
                $mb_class = ($transaction_manual_bot === "MANUAL") ? "green" : (($transaction_manual_bot === "BOT") ? "blue" : "");
                echo "<td><button type='button' class='transaction_button ".$mb_class."' name='manual_bot'>".$transaction_manual_bot."</button></td>";
            } else {
                echo "<td><button type='button' class='transaction_button' name='manual_bot'><i class='fa fa-plus'></i> Manual / Bot</button></td>";
            }

            echo "<td><button type='button' class='transaction_button' name='add_note'><i class='fa fa-plus'></i> Add note</button></td>";
            
            echo "<td><button type='button' class='transaction_button' name='see_transaction' data-id='$transaction_id'><i class='fa fa-eye'></i> See transaction</button></td>";

            echo "<td><button type='button' class='transaction_button' name='close_transaction' data-id='$transaction_id'><i class='fa fa-times'></i></button></td>";
        echo "</tr>";
    }
        
?>