<?php
    include_once 'includes/dbconnect.php';
    include_once 'includes/functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Portforlio Tracker - Providers</title>
        <link rel="stylesheet" href="css/style.css?<?php echo time() ?>" />
        <link rel="stylesheet" href="css/providers.css?<?php echo time() ?>" />
        <!-- <link rel="stylesheet" href="css/message.css?<?php echo time() ?>" /> -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link href='https://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
        <link rel="icon" type="image/png" sizes="32x32" href="investment.png">
        <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
        <script src="js/clock.js?<?php echo time() ?>" defer></script>
        <script src="js/providers.js?<?php echo time() ?>" defer></script>
</head>
<body>
     <header>
          <a href="."><img src="portfolio-ticker-logo.svg" alt="Portfolio Ticker" /></a>
          <div class="clockWrapper"><button type ="button" class="secondary" name="worldclock"  id="worldclock">World Clock</button><div id="clock">--:--:--</div></div>
      </header>
        <div class="container">
            <div class="sidebar">
                <nav>
                    <ul>
                        <?php
                            $base = (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'localhost') ? 'http://localhost/portfoliotracker/' : 'https://portfoliotracker.tmisura.sk/';
                        ?>

                        <li><a href="<?= $base ?>index.php"><i class="fas fa-home"></i> Domov</a></li>
                        <li><a href="<?= $base ?>/portfolio/index.php"><i class="fas fa-chart-line"></i> Portfólio</a></li>
                        <li><a href="<?= $base ?>providers.php"><i class="fas fa-building"></i> Poskytovatelia</a></li>
                         <li><a href="<?= $base ?>notes.php"><i class="fas fa-sticky-note"></i> Poznámky</a></li>
                       <!--  <li><a href="<?= $base ?>influencers.php"><i class="fas fa-users"></i> Influencers</a></li>
                        <li><a href="<?= $base ?>news.php"><i class="fas fa-newspaper"></i> Novinky</a></li>
                        <li><a href="<?= $base ?>settings.php"><i class="fas fa-cogs"></i> Nastavenia</a></li>
                        -->
                        <li><a href="<?= $base ?>logout.php"><i class="fas fa-sign-out-alt"></i> Odhlásiť sa</a></li>
                    </ul>
                </nav>
            </div>
            <div class="content">
                <h1>Poskytovatelia</h1>
                <p>Tu môžete spravovat poskytovateľov finančných služieb, ktoré používate pre svoje investície.</p> 

                <button type="button" id="btnAddNewProvider" class="button small_button" name="new_provider" title="add new provider"><i class="fa fa-plus"></i></button>
                

                <div class="providers">
                    <?php
                        // Example: Fetch providers from database and display them
                        
                        $get_providers = "SELECT * FROM providers ORDER BY provider_name ASC";
                        $result = mysqli_query($link, $get_providers) or die("MySQL ERROR: " . mysqli_error($link));
                        while ($row = mysqli_fetch_array($result)) {
                        
                        $provider_id = $row['id'];
                        $provider_name = $row['provider_name'];
                        $provider_logo = $row['provider_logo'];
                        $provider_description = $row['provider_description'];


                        echo '<div class="provider_card" data-id="'.$provider_id.'" data-name="'.$provider_name.'">'.$provider_name.'</div>';
                        }
                
                        ?>

                </div><!--providers-->
            <div class="provider_details">
            </div><!--provider_details-->    

            </div><!--content-->  
        </div><!--container-->      
    <dialog id="modalAddNewProvider">
        <div class="modal-container">
            <div id="modalAddNewPRoviderContent">
                <input type="text" id="provider_name" placeholder="provider name..." autocomplete="off">
                <input type="text" id="provider_url" placeholder="provider url..." autocomplete="off">
                <input type="text" id="provider_logo" placeholder="provider logo url..." autocomplete="off">
                <textarea id="provider_description" placeholder="provider description..." autocomplete="off"></textarea>
                <div id="modalAddNewProviderButtons">
                    <button type="button" id="btnSaveNewProvider" class="button small_button" name="save_new_provider" title="save new provider"><i class="fa fa-save"></i></button>
                    <button type="button" id="btnCancelNewProvider" class="button small_button" name="cancel_new_provider" title="cancel new provider"><i class="fa fa-times"></i></button>
                </div>    
            </div>
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

<dialog id="modalAssetCategory">
    <div class="modal-container">
        <h3>Asset category</h3>
        <div id="assetCategoryDetailsContent">
            <button type="button" class="button" data-filter="stocks">Akcie</button>
            <button type="button" class="button" data-filter="crypto">Kryptomeny</button>
            <button type="button" class="button" data-filter="etf">ETF</button>
            <button type="button" class="button" data-filter="options">Opcie</button>
            <button type="button" class="button" data-filter="bonds">Dlhopis(y)</button>
            <button type="button" class="button" data-filter="forex">Forex</button>
            <button type="button" class="button" id="assetModalClose">Zatvoriť</button>
        </div>
    </div>
</dialog>

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
</body>

</html>