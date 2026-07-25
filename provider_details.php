<?php
    include_once 'includes/dbconnect.php';
    include_once 'includes/functions.php';

    $providerId = $_GET['providerId'] ?? null;


    $getProvideDatails = "SELECT * FROM providers WHERE id = $providerId";
    $result = mysqli_query($link, $getProvideDatails) or die(mysqli_error($link));
    
    echo "<div class='provider'>";  
        while($row = mysqli_fetch_assoc($result)) {
            $providerName = $row['provider_name'];
            $providerDescription = $row['provider_description'];
            $providerLogo = parseProviderLogo($row['provider_logo']);
            $providerUrl = $row['provider_url'];

            echo "<div class='provider_name_wrapper'><h2>$providerName</h2>";
            if (!empty($providerLogo)) {
                echo "<div class='provider_logo'><img src='$providerLogo' alt='$providerName logo'></div>";
            }else{
                echo "<div class='provider_logo_save_wrapper'><input type='text' class='provider_logo_url_input' placeholder='Logo URL' /><button type='button' class='button small_button btn_save_logo' title='Uložiť logo'><i class='fas fa-save'></i></button></div>";
            }
            echo "</div>";
            echo "<div class='provider_description' contenteditable='true'>$providerDescription</div>";
            echo "<div class='provider_url'><a href='$providerUrl' target='_blank'>Visit Website</a> <button type='button' class='button small_button' name='edit_provider_url' title='edit provider url'>Edit</button></div>";
        }

    echo "</div>";     
    
    echo "<div class='provider_transactions'>";
        echo "<div class='provider_transactions_header'>  
                <h3>Transactions</h3><button type='button' name='add_transaction'>Pridat transakciu</button>
            </div>";
            echo "<div class='transaction_actions_tabs'>";
                echo "<button type='button' name='all_transactions'>All transactions</button>";
                echo "<button type='button' name='active_transactions'>Active transactions</button>";
                echo "<button type='button' name='closed_transactions'>Closed transactions</button>";
                echo "<button type='button' name='new_transaction'>New transaction</button>";
            echo "</div>";
            echo "<div class='provider_transactions_body'>";
                echo "<div class='loader'>Loading transactions...</div>";
            echo "</div>";
    echo "</div>";