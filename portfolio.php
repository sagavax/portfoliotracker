<?php
  include('includes/dbconnect.php');
  include('includes/functions.php');

  session_start();

  if(!isset($_SESSION['login'])) {
    header('location:login.php');
  }

  ?>


<!DOCTYPE html>
<html lang="en">
  <body>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Portforlio Tracker</title>
        <link rel="stylesheet" href="css/style.css?<?php echo time() ?>" />
        <link rel="stylesheet" href="css/portfolio.css?<?php echo time() ?>" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link href='https://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
        <link rel="icon" type="image/png" sizes="32x32" href="investment.png">
        <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
        <script src="js/clock.js?<?php echo time() ?>" defer></script>
        <script src="js/portfolio.js?<?php echo time() ?>" defer></script>
        <script src="js/messsage.js?<?php echo time() ?> defer"></script>
        <!-- <script src="js/worldclock.js?<?php echo time() ?>"></script> -->
        
    </head>

        <header>
          <a href="."><img src="portfolio-ticker-logo.svg" alt="Portfolio Ticker"></a><div class="clockWrapper"><button type ="button" class="secondary" name="worldclock"  id="worldclock">World Clock</button><div id="clock">--:--:--</div></div>
        </header>
        <div class="container">
            <div class="sidebar">
                <nav>
                    <ul>
                        <li><a href="index.php"><i class="fas fa-home"></i> Domov</a></li>
                        <li><a href="portfolio.php"><i class="fas fa-chart-line"></i> Portfólio</a></li>
                        <li><a href="providers.php"><i class="fas fa-building"></i> Poskytovatelia</a></li>
                        <li><a href="influencers.php"><i class="fas fa-users"></i> Influencers</a></li>
                        <li><a href="news.php"><i class="fas fa-newspaper"></i> Novinky</a></li>
                        <li><a href="settings.php"><i class="fas fa-cogs"></i> Nastavenia</a></li>
                        <li><a href="notes.php"><i class="fas fa-sticky-note"></i> Poznámky</a></li>
                        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Odhlásiť sa</a></li>
                    </ul>
                </nav>
            </div>
            <div class="content">
                <h1>Vitajte v Portfolio Tracker Notes</h1>
                <p>Tu můžete přidávat poznámky k jednotlivým transakcím.</p>
                
                
                <div class="filter_active_providers">
                    <h3>Aktivní poskytovatelia:</h3>
                    <?php
                     //list of providets where is
                    
                    ?>
                 </div>
                
                 <div class="transactions_filter">
                    <button type="button" class="filter_button" data-filter="all">Vše</button>
                    <button type="button" class="filter_button" data-filter="stocks">Akcie</button>
                    <button type="button" class="filter_button" data-filter="crypto">Kryptoměny</button>
                    <button type="button" class="filter_button" data-filter="etf">ETF</button>
                    <button type="button" class="filter_button" data-filter="other">Ostatní</button>
                    <button type="button" class="filter_button" data-filter="active">Aktivní</button>
                    <button type="button" class="filter_button" data-filter="closed">Uzavřené</button>                    
                    <button type="button" name="add_transation" class="button small_button" id="btnAddNewtransaction"><i class="fa fa-plus"></i> Přidat transakci</button>
                    
                   <!-- create new transaction -->
                    <div class="create_transaction_wrapper">
                        <button type="button" name="add_ticker" class="button small_button" id="add_ticker"><i class="fa fa-plus"></i> Add ticker</button>
                        <button type="button" name="add_provider" class="button small_button" id="add_provider"><i class="fa fa-plus"></i> Add provider</button>
                        <button type="button" name="add_asset_category" class="button small_button" id="add_asset_category"><i class="fa fa-plus"></i> Add category</button>
                        <button type="button" name="add_entry_price" class="button small_button" id="add_entry_price"><i class="fa fa-plus"></i> Add price</button>
                        <button type="button" name="add_quantity" class="button small_button" id="add_quqntity"><i class="fa fa-plus"></i> Add quantity</button>
                        <button type="button" name="add_tp" class="button small_button"><i class="fa fa-plus"></i> Add TP</button>
                        <button type="button" name="add_sl" class="button small_button"><i class="fa fa-plus"></i> Add SL</button>
                        <button type="button" name="long_short"><i class="fa fa-plus"></i> Long/Short</button>
                        <button type="button" name="add_currency"><i class="fa fa-plus"></i> Currency</button> 
                        <button type="button" name="spot_perpetual" class="button small_button" id="spot_perpetual"><i class="fa fa-plus"></i> Spot/Perpetual</button>
                        <button type="button" name="add_note" class="button small_button" id="add_note"><i class="fa fa-plus"></i> Add note</button>
                    </div><!--new_transaction-->
                    <div class="new_transaction">
                        <div class="new_transaction_actions_wrapper">
                            <button name="new_transaction_create" class="secondary">Create</button>
                            <button name="new_transaction_reset" class="secondary">Reset</button>
                            <button name="new_transaction_cancel" class="secondary">Cancel</button>
                        </div>   
                    </div>    
                 </div>
                <div class="transactions">
                    <table>
                        <thead>

                        </thead>
                        <tbody>
                            <?php
                            //list of transactions  
                                $get_transations = "SELECT * FROM transactions WHERE is_closed = 0 ORDER BY created_at DESC";
                                $result = mysqli_query($link, $get_transations) or die("MySQL ERROR: " . mysqli_error($link));
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
                                    $transaction_long_short = $row['type'];
                                    $transaction_created_at = $row['created_at'];
                                    $transaction_spot_perpetual = $row['spot_perpetual'];

                                    echo "<tr class='transaction' data-id='$transaction_id'>";
                                        echo "<td><button type='button' class='button' name='ticker'>$transaction_ticker</button></td>";
                                        
                                        if($transaction_provider) {
                                            echo "<td><button type='button' class='button' name='provider'>$transaction_provider</button></td>";
                                        } else {
                                            echo "<td><button type='button' class='button' name='provider'><i class='fa fa-plus'></i> Add provider</button></td>";
                                        }
                                        
                                        
                                        if($transaction_category) {
                                            echo "<td><button type='button' class='button' name='category'>$transaction_category</button></td>";
                                        } else {
                                            echo "<td><button type='button' class='button' name='category'><i class='fa fa-plus'></i> Add category</button></td>";
                                        }
                                        
                                        echo "<td><button type='button' class='button' name='currency'>$transaction_currency</button>";
                                        
                                        if($transaction_quantity != 0) {
                                            echo "<td><div class='quantity'>$transaction_quantity</div></td>";
                                        } else {
                                            echo "<td><button type='button' class='button' name='add_quantity'><i class='fa fa-plus '></i> Add quantity</button></td>";
                                        }
                                        
                                        if($transaction_entry_price != 0.0) {
                                            echo "<td><div class='price'>$transaction_entry_price</div></td>";
                                        } else {
                                            echo "<td><button type='button' class='button' name='add_entry_price'><i class='fa fa-plus'></i> Add price</button></td>";
                                        }
                                        
                                        $tp_display = ($transaction_tp == 0.0) ? "TP" : $transaction_tp;
                                        $sl_display = ($transaction_sl == 0.0) ? "SL" : $transaction_sl;
                                        echo "<td style='display: flex; gap: 10px;'><button type='button' class='button' name='take_profit'>".$tp_display."</button> / <button type='button' class='button' name='stop_loss'>".$sl_display."</button></td>";
                                        
                                        
                                        if($transaction_long_short) {
                                            $ls_class = ($transaction_long_short === "BUY") ? "long" : (($transaction_long_short === "SELL") ? "short" : "");
                                            echo "<td><button type='button' class='button ".$ls_class."' name='long_short'>".$transaction_long_short."</button></td>";
                                        } else {
                                            echo "<td><button type='button' class='button' name='long_short'><i class='fa fa-plus'></i> Long/Short</button></td>";
                                        }
                                        
                                        if($transaction_spot_perpetual) {
                                            $sp_class = ($transaction_spot_perpetual === "Spot") ? "green" : (($transaction_spot_perpetual === "Perpetual") ? "blue" : "");
                                            echo "<td><button type='button' class='button ".$sp_class."' name='spot_perpetual'>".$transaction_spot_perpetual."</button></td>";
                                        } else {
                                            echo "<td><button type='button' class='button' name='spot_perpetual'><i class='fa fa-plus'></i> Spot / Perpetual</button></td>";
                                        }
                                        
                                        echo "<td><button type='button' class='button' name='add_note'><i class='fa fa-plus'></i> Add note</button></td>";
                                        
                                        echo "<td><button type='button' class='button' name='see_transaction' data-id='$transaction_id'><i class='fa fa-eye'></i> See transaction</button></td>";

                                        echo "<td><button type='button' class='button' name='close_transaction' data-id='$transaction_id'><i class='fa fa-times'></i></button></td>";
                                    echo "</tr>";
                                }
                                
                            ?>
                        </tbody>
                    </table>
                </div>    
            </div>  
        </div><!--container-->  
  </body>
</html>


<dialog id="modalTakeProfit">
  <div class="modal-container">
    <div id="modalTakeProfitContent"><input type="text" placeholder="Take profit" autocomplete="off"></div>
  </div>
</dialog>

<dialog id="modalStopLoss">
  <div class="modal-container">
    <div id="modalStopLossContent"><input type="text" placeholder="Stop loss" autocomplete="off"></div>
  </div>
</dialog>

<dialog id="AssetCategoryModal">
    <div class="modal-container">
        <h3>Asset category</h3>
        <div id="assetCategoryDetailsContent">
            <button type="button" class="buton" data-filter="stocks">Akcie</button>
            <button type="button" class="button" data-filter="crypto">Kryptomeny</button>
            <button type="button" class="button" data-filter="etf">ETF</button>
            <button type="button" class="button" data-filter="options">Opcie</button>
            <button type="button" class="button" data-filter="bonds">Dlhopis(y)</button>
            <button type="button" class="button" data-filter="forex">Forex</button>
            <button type="button" class="button" id="assetModalClose" >Zatvoriť</button>
        </div>        
    </div>
</dialog>

<dialog id="ProviderModal">
    <div class="modal-container">
        <h3>Provider details</h3>
        <div id="providerDetailsContent"></div>
        <button id="providerModalClose" class="secondary">Zatvoriť</button>
    </div>
</dialog>


<dialog id="TickerModal">
    <div class="modal-container">
        <h3>Ticker details</h3>
        <div class="filter_tickers">
            <?php foreach (range('A', 'Z') as $letter): ?>
                <button type="button" class="secondary" data-letter="<?= $letter ?>"><?= $letter ?></button>
            <?php endforeach; ?>
        </div>
        
        <div class="search_wrapper">
            <input type="text" name="search_ticker" id="search_in_ticker" placeholder="Hledat v tickeroch...">
        </div>  

        <div id="tickerDetailsContent"></div>
        <button id="tickerModalClose" class="secondary">Zatvoriť</button>
    </div>
</dialog>

<dialog id="LongShortModal">
    <div class="modal-container">
        <h3>Long/Short details</h3>
        <div id="longShortDetailsContent">
            <button type="button" name="add_long" class="button small_button green" id="add_long"><i class="fa fa-plus"></i> Add Long</button>
            <button type="button" name="add_short" class="button small_button red" id="add_short"><i class="fa fa-plus"></i> Add Short</button>
        </div>
        
        <button id="longShortModalClose" class="secondary">Zatvoriť</button>
    </div>
</dialog>

<dialog id="SpotPerpetualModal">
    <div class="modal-container">
        <h3>Spot/Perpetual details</h3>
        <div id="spotPerpetualDetailsContent">
            <button type="button" name="add_spot" class="button small_button green" id="add_spot"><i class="fa fa-plus"></i> Add Spot</button>
            <button type="button" name="add_perpetual" class="button small_button blue" id="add_perpetual"><i class="fa fa-plus"></i> Add Perpetual</button>
        </div>
        
        <button id="spotPerpetualModalClose" class="secondary">Zatvoriť</button>
    </div>
</dialog>

<dialog id="NoteModal">
    <div class="modal-container">
        <h3>Note details</h3>
        <div id="noteDetailsContent"><textarea id="note_text"></textarea></div>
        <button id="noteModalSave" class="secondary">Uložiť</button>
        <button id="noteModalClose" class="secondary">Zatvoriť</button>
    </div>
</dialog>

<dialog id="modalPrice" class="modal-overlay">
  <div class="modal-container">
    <div id="modalPriceContent"><input type="text" placeholder="Cena" autocomplete="off"></div>
  </div>
</dialog>

<dialog id="modalQuantity" class="modal-overlay">
  <div class="modal-container">
    <div id="modalQuantityContent"><input type="text" placeholder="Quantity" autocomplete="off"></div>
  </div>
</dialog>

<dialog id="modalCurrency">
  <div class="modal-container">
    <div id="modalCurrencyContent">
        <button type="button" name="EUR" class="secondary">EUR</button>
        <button type="button" name="USD" class="secondary">USD</button>
        <button type="button" name="CZK" class="secondary">CZK</button>
        <button type="button" name="GBP" class="secondary">GBP</button>
        <button type="button" name="JPY" class="secondary">JPY</button>
        <button type="button" name="CHF" class="secondary">CHF</button>
        <button type="button" name="CAD" class="secondary">CAD</button>
        <button type="button" name="AUD" class="secondary">AUD</button>
        <button type="button" name="HKD" class="secondary">HKD</button>
        <button type="button" name="SEK" class="secondary">SEK</button>
    </div>
  </div>
</dialog>

<dialog id="modalAddNote">
  <div class="modal-container">
    <div id="modalAddNoteContent"><textarea placeholder="Add note" autocomplete="off"></textarea></div>
    <button id="saveNote" class="secondary">Save</button>
  </div>
</dialog>