<?php
  include('../includes/dbconnect.php');
  include('../includes/functions.php');

  session_start();

  if(!isset($_SESSION['login'])) {
    header('Location: ../login.php');
    exit;
  }
?>


<!DOCTYPE html>
<html lang="en">
  <body>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Portforlio Tracker</title>
        <link rel="stylesheet" href="../css/style.css?<?php echo time() ?>" />
        <link rel="stylesheet" href="css/portfolio.css?<?php echo time() ?>" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link href='https://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
        <link rel="icon" type="image/svg+xml" sizes="32x32" href="../investment.png">
        <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
        <script src="../js/clock.js?<?php echo time() ?>" defer></script>
        <script src="js/portfolio.js?<?php echo time() ?>" defer></script>
        <script src="../js/message.js?<?php echo time() ?> defer"></script>
        <!-- <script src="../js/worldclock.js?<?php echo time() ?>"></script> -->
    </head>

        <header>
          <a href="."><img src="../portfolio-ticker-logo.svg" alt="Portfolio Ticker"></a><div class="clockWrapper"><button type ="button" class="secondary" name="worldclock"  id="worldclock">World Clock</button><div id="clock">--:--:--</div></div>
        </header>
        <div class="container">
            <div class="debug" style="display: flex; color:white"><?php echo $_SERVER['HTTP_HOST']; ?></div>
            <div class="sidebar">
                <nav>
                    <ul>
                        <?php
                            $base = (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'localhost') ? 'http://localhost/portfoliotracker/' : 'https://portfoliotracker.tmisura.sk/';

                        ?>

                        <li><a href="<?= $base ?>index.php"><i class="fas fa-home"></i> Domov</a></li>
                        <li><a href="<?= $base ?>/portfolio/index.php"><i class="fas fa-chart-line"></i> Portfólio</a></li>
                        <li><a href="<?= $base ?>providers.php"><i class="fas fa-building"></i> Poskytovatelia</a></li>
                        <li><a href="<?= $base ?>tickers.php"><i class="fas fa-tags"></i> Tickers</a></li>
                       <!--  <li><a href="<?= $base ?>influencers.php"><i class="fas fa-users"></i> Influencers</a></li>
                        <li><a href="<?= $base ?>news.php"><i class="fas fa-newspaper"></i> Novinky</a></li>
                        <li><a href="<?= $base ?>settings.php"><i class="fas fa-cogs"></i> Nastavenia</a></li>
                        <li><a href="<?= $base ?>notes.php"><i class="fas fa-sticky-note"></i> Poznámky</a></li> -->
                        <li><a href="<?= $base ?>logout.php"><i class="fas fa-sign-out-alt"></i> Odhlásiť sa</a></li>

                    </ul>
                </nav>
            </div>
            <div class="content">
                <h1>Vitajte v Portfolio Tracker</h1>
                <p>Zoznam jednotlivych transakci.</p>
                
                
                 <div class="transactions_filters">

                     <div class="create_transaction_action_wrapper">
                        <button type="button" name="add_transation" class="button small_button" id="btnAddNewtransaction"><i class="fa fa-plus"></i> Přidat transakci</button>
                    </div><!-- create_transaction_action_wrapper -->

                    <div class="transaction_filters_buttons">
                        <div class="filter_all">
                            <button type="button" class="filter_button" data-filter="all">Vše</button>
                        </div>

                        <div class="filter_asset_category">
                            <button type="button" class="filter_button" data-filter="stocks">Akcie</button>
                            <button type="button" class="filter_button" data-filter="crypto">Kryptoměny</button>
                            <button type="button" class="filter_button" data-filter="etf">ETF</button>
                            <button type="button" class="filter_button" data-filter="options">Opcie</button>
                            <button type="button" class="filter_button" data-filter="bonds">Dlhopis(y)</button>
                            <button type="button" class="filter_button" data-filter="forex">Forex</button>
                            <button type="button" class="filter_button" data-filter="commodities">Komoditý</button>
                            <button type="button" class="filter_button" data-filter="indices">Indexy</button>
                        </div><!-- filter_asset_category -->
                        
                       


                        <div class="filter_currency">
                            <select name="currency" id="currency">
                               <option value="all">All</option>     
                            <?php
                                $get_currencies = "SELECT currency FROM transactions GROUP BY currency ORDER BY currency ASC";
                                $result = mysqli_query($link, $get_currencies) or die("MySQL ERROR: " . mysqli_error($link));
                                while ($row = mysqli_fetch_array($result)) {
                                    $currency = $row['currency'];
                                    echo "<option value='$currency'>$currency</option>";
                                }
                            ?>
                            </select>
                        </div><!-- filter_currency -->


                        <div class="filter_spot_perpetual">
                            <button type="button" class="filter_button" data-filter="spot">Spot</button>
                            <button type="button" class="filter_button" data-filter="perpetual">Perpetual</button>
                        </div>
                       

                        <div class="filter_manual_bot">
                            <button type="button" class="filter_button" data-filter="manual">Manual</button>
                            <button type="button" class="filter_button" data-filter="bot">Bot</button>
                        </div>
                                                
                        <div class="filter_long_short">
                            <button type="button" class="filter_button" data-filter="long">Long</button>
                            <button type="button" class="filter_button" data-filter="short">Short</button>
                        </div>

                      <!--   <div class="filter_providers">
                            <?php
                                $get_providers = "SELECT provider_name FROM providers ORDER BY provider_name ASC";
                                $result = mysqli_query($link, $get_providers) or die("MySQL ERROR: " . mysqli_error($link));
                                while ($row = mysqli_fetch_array($result)) {
                                    $provider_name = $row['provider_name'];
                                    echo "<button type='button' class='filter_button' data-filter='$provider_name'>$provider_name</button>";
                                }
                            ?>
                        </div> -->

                        <div class="filter_active_closed">
                            <button type="button" class="filter_button" data-filter="active">Aktivní</button>
                            <button type="button" class="filter_button" data-filter="closed">Uzavřené</button>                    
                        </div>

                    </div><!-- transaction_filters_buttons -->
                    

                 </div><!-- transactions_filters -->    
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

                <div class="filter_ticker">
                    <?php
                        $get_tickers = "SELECT symbol FROM transactions GROUP BY symbol ORDER BY symbol ASC";
                        $result = mysqli_query($link, $get_tickers) or die("MySQL ERROR: " . mysqli_error($link));
                        while ($row = mysqli_fetch_array($result)) {
                            $ticker = $row['symbol'];
                            echo "<button type='button' class='filter_button' data-filter='$ticker'>$ticker</button>";
                        }
                    ?>
                </div><!-- filter_ticker -->    
                
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
                                    $transaction_category = $row['asset_category'];
                                    $transaction_currency = $row['currency'];
                                    $transaction_leverage = $row['leverage'];
                                    $transaction_quantity = $row['quantity'];
                                    $transaction_entry_price = $row['entry_price'];
                                    $transaction_tp = $row['tp_price'];
                                    $transaction_sl = $row['sl_price'];
                                    $transaction_long_short = $row['position_type'];
                                    $transaction_created_at = $row['created_at'];
                                    $transaction_spot_perpetual = $row['spot_perpetual'];
                                    $transaction_manual_bot = $row['manual_bot'];

                                    echo "<tr class='transaction' data-id='$transaction_id'>";
                                        
                                    // Duplicate transaction button
                                    echo "<td><button type='button' class='transaction_button' title='Duplicate transaction' name='duplicate_transaction' transaction_id='$transaction_id'><i class='fa fa-copy'></i></button></td>";

                                        if($transaction_ticker) {
                                            echo "<td><button type='button' class='transaction_button' name='ticker' data-ticker='existing_ticker'>$transaction_ticker</button></td>";
                                        } else {
                                            echo "<td><button type='button' class='transaction_button' name='ticker' data-ticker='add_ticker'><i class='fa fa-plus'></i> Add ticker</button></td>";
                                        }
                                        
                                        if($transaction_provider) {
                                            echo "<td><button type='button' class='transaction_button' name='provider' data-provider='existing_provider'>$transaction_provider</button></td>";
                                        } else {
                                            echo "<td><button type='button' class='transaction_button' name='provider' data-provider='add_provider'><i class='fa fa-plus'></i> Add provider</button></td>";
                                        }
                                        
                                        
                                        if($transaction_category) {
                                            echo "<td><button type='button' class='transaction_button' name='category' data-category='existing_category'>$transaction_category</button></td>";
                                        } else {
                                            echo "<td><button type='button' class='transaction_button' name='category' data-category='add_category'><i class='fa fa-plus'></i> Add category</button></td>";
                                        }
                                        
                                        echo "<td><button type='button' class='transaction_button' name='currency' data-currency='existing_currency'>$transaction_currency</button></td>";

                                        if($transaction_leverage != 0) {
                                            echo "<td><div class='leverage' contenteditable='true'>$transaction_leverage</div></td>";
                                        } else {
                                            echo "<td><button type='button' class='transaction_button' name='add_leverage'><i class='fa fa-plus'></i> Add leverage</button></td>";
                                        }

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

                                        $nr_notes = GetCountTransactionNotes($transaction_id);
                                        if($nr_notes > 0) {
                                            echo "<td><button type='button' class='transaction_button' name='notes'>$nr_notes</button></td>";
                                        } else {
                                            echo "<td><button type='button' class='transaction_button' name='notes'>0</button></td>";
                                        }
                                        
                                        echo "<td><button type='button' class='transaction_button' name='see_transaction' data-id='$transaction_id'><i class='fa fa-eye'></i> See transaction</button></td>";

                                        echo "<td><div class='close_transaction_wrapper' data-id='$transaction_id'><button type='button' class='transaction_button' name='close_transaction' data-id='$transaction_id'><i class='fa fa-times'></i></button></div></td>";
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

<dialog id="modalAssetCategory">
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

<dialog id="modalProvider">
    <div class="modal-container">
        <h3>Provider details</h3>
        <div id="providerDetailsContent">
            <div class="loader">Loading...</div>
         </div>
        <button id="btnProviderModalClose" class="secondary">Zatvoriť</button>
    </div>
</dialog>


<dialog id="modalTicker">
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

<dialog id="modalLongShort">
    <div class="modal-container">
        <h3>Long/Short details</h3>
        <div id="longShortDetailsContent">
            <button type="button" name="add_long" class="button small_button green" id="add_long"><i class="fa fa-plus"></i> Add Long</button>
            <button type="button" name="add_short" class="button small_button red" id="add_short"><i class="fa fa-plus"></i> Add Short</button>
        </div>
        
        <button id="longShortModalClose" class="secondary">Zatvoriť</button>
    </div>
</dialog>

<dialog id="modalSpotPerpetual">
    <div class="modal-container">
        <h3>Spot/Perpetual details</h3>
        <div id="spotPerpetualDetailsContent">
            <button type="button" name="add_spot" class="button small_button green" id="add_spot"><i class="fa fa-plus"></i> Add Spot</button>
            <button type="button" name="add_perpetual" class="button small_button blue" id="add_perpetual"><i class="fa fa-plus"></i> Add Perpetual</button>
        </div>
        
        <button id="spotPerpetualModalClose" class="secondary">Zatvoriť</button>
    </div>
</dialog>

<dialog id="modalNote">
    <div class="modal-container">
        <h3>Note details</h3>
        <div id="noteDetailsContent"><textarea id="note_text"></textarea></div>
        <div class="modal_note_actions">
            <button id="noteSave" class="secondary">Uložiť</button>
            <button id="noteClose" class="secondary">Zatvoriť</button>
        </div>
        
    </div>
</dialog>

<dialog id="modalNotes">
    <div class="modal-container">
         <div id="notesDetailsContent">
            Loading...
         </div>
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
        <button type="button" data-currency="EUR" class="secondary">EUR</button>
        <button type="button" data-currency="USD" class="secondary">USD</button>
        <button type="button" data-currency="CZK" class="secondary">CZK</button>
        <button type="button" data-currency="GBP" class="secondary">GBP</button>
        <button type="button" data-currency="JPY" class="secondary">JPY</button>
        <button type="button" data-currency="CHF" class="secondary">CHF</button>
        <button type="button" data-currency="CAD" class="secondary">CAD</button>
        <button type="button" data-currency="AUD" class="secondary">AUD</button>
        <button type="button" data-currency="HKD" class="secondary">HKD</button>
        <button type="button" data-currency="SEK" class="secondary">SEK</button>
    </div>
  </div>
</dialog>

<dialog id="modalAddNote">
  <div class="modal-container">
    <div id="modalAddNoteContent"><textarea placeholder="Add note" autocomplete="off"></textarea></div>
    <button id="saveNote" class="secondary">Save</button>
  </div>
</dialog>

<dialog id="modalManualBot">
    <div class="modal-container">
        <h3>Manual bot</h3>
        <div id="manualBotDetailsContent">
            <button type="button" name="manual_bot_on" class="button small_button green" id="manual_bot_on"><i class="fa fa-plus"></i> Manual</button>
            <button type="button" name="manual_bot_off" class="button small_button red" id="manual_bot_off"><i class="fa fa-plus"></i> Bot</button>
        </div>
        <button id="manualBotModalClose" class="secondary">Close</button>
    </div>
</dialog>

<dialog id="modalLeverage">
  <div class="modal-container">
    <div id="modalLeverageContent">
        <input type="range" min="0" max="100" step="1" value="0" id="leverageSlider">
        <input type="text" placeholder="0" autocomplete="off" id="leverageInput">
    </div>
    <div class="leverage_actions">
        <button id="leverageCancel" class="secondary">Cancel</button>
        <button id="saveLeverage" class="secondary">Save</button>
    </div>    
  </div>
</dialog>  