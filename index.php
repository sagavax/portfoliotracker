<?php

/*   include('includes/dbconnect.php');
  include('includes/functions.php'); */


  ?>


<!DOCTYPE html>
<html lang="en">
  <body>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Portforlio Tracker</title>
        <link rel="stylesheet" href="css/style.css?<?php echo time() ?>" />
        <link rel="stylesheet" href="css/index.css?<?php echo time() ?>" />
        <!-- <link rel="stylesheet" href="css/message.css?<?php echo time() ?>" /> -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link href='https://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
        <link rel="icon" type="image/png" sizes="32x32" href="investment.png">
         <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
        <script src="js/clock.js?<?php echo time() ?>" defer></script>
        <!-- <script src="js/worldclock.js?<?php echo time() ?>"></script> -->
        
    </head>

        <header>
          <a href="."><img src="portfolio-ticker-logo.svg" alt="Portfolio Ticker"></a><div class="clockWrapper"><button type ="button" class="secondary" name="worldclock"  id="worldclock">World Clock</button><div id="clock">--:--:--</div></div>
        </header>
        <div class="container">
            <div class="sidebar">
                <nav>
                    <ul>
                        <?php
                           $base = (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'localhost') ? 'http://localhost/portfoliotracker/' : 'https://portfoliotracker.tmisura.sk/';
                        ?>

                        <li><a href="<?= $base ?>index.php"><i class="fas fa-home"></i> Domov</a></li>
                        <li><a href="<?= $base ?>portfolio/index.php"><i class="fas fa-chart-line"></i> Portfólio</a></li>
                        <li><a href="<?= $base ?>providers.php"><i class="fas fa-building"></i> Poskytovatelia</a></li>
                         <li><a href="<?= $base ?>tickers.php"><i class="fas fa-tags"></i> Tickers</a></li>
                        <!-- <li><a href="<?= $base ?>influencers.php"><i class="fas fa-users"></i> Influencers</a></li>
                        <li><a href="ews.php"><i class="fas fa-newspaper"></i> Novinky</a></li>
                        <li><a href="<?= $base ?>settings.php"><i class="fas fa-cogs"></i> Nastavenia</a></li>
                        <li><a href="<?= $base ?>notes.php"><i class="fas fa-sticky-note"></i> Poznámky</a></li> -->
                        <li><a href="<?= $base ?>logout.php"><i class="fas fa-sign-out-alt"></i> Odhlásiť sa</a></li>
                    </ul>
                </nav>
            </div>
            <div class="content">
                <h1>Vitajte v Portfolio Tracker</h1>
                <p>Tu môžete sledovat svoje investície, spravovat portfólio a získavať novinky z finančného sveta.</p>
                <div class=dashboard>
                    <div class="dashboard-item">Portfolio</div>
                    <div class="dashboard-item">Poskytovatelia</div>
                    <div class="dashboard-item">Influencers</div>
                    <div class="dashboard-item">Novinky</div>
                    <div class="dashboard-item">Poznámky</div>
                    <div class="dashboard-item">Nastavenia</div>
                </div>                    
            </div>  
        </div><!--container-->  
  </body>
</html>

<!-- Note modal -->
<div id="noteModal" class="modal-overlay">
  <div class="modal-container">
    <h3>Pridať poznámku</h3>
  <!-- <div id="noteExistingList" style="max-height:160px;overflow:auto;margin-bottom:8px;border:1px solid #f0f0f0;padding:8px;border-radius:6px;background:#fafafa;font-size:13px;color:#222"></div> -->
  <textarea id="noteText" placeholder="Napíš novú poznámku sem..." class="modal-textarea"></textarea>
    <div class="modal-actions">
      <button id="noteCancel" class="secondary">Zrušiť</button>
      <button id="noteSave">Uložiť</button>
    </div>
  </div>
</div>

<!-- Notes list modal -->
<div id="notesListModal" class="modal-overlay">
  <div class="modal-container-large">
    <h3>Zoznam poznámok</h3>
    <div id="notesListContent" class="modal-content"></div>
    <div class="modal-actions">
      <button id="notesListClose" class="secondary">Zatvoriť</button>
    </div>
  </div>
</div>
</html>
 

<dialog id="modalModifyPosition" class="modal-overlay">
  <div class="modal-container">
    <h3>Uprava položky</h3>
    <div id="modalModifyPosiotionContent">
        <input type="number" id="modalModifyPosiotionQty" placeholder="Množstvo" autocomplete="off">
        <input type="number" id="modalModifyPosiotionPrice" placeholder="Cena" autocomplete="off">
        <button id="modalModifyPosiotionSave" type="button" ckass="secondary">Uložiť</button>
    </div>
    <div class="modal-actions">
      <button id="modalModifyPosiotionClose" class="secondary">Zatvoriť</button>
    </div>
  </div>
</dia>


<dialog id="modalStopLossTakeProfit" class="modal-overlay">
  <div class="modal-container">
   <h3>Uprava Stop loss a Take profit</h3>
    <div id="modalStopLossTakeProfitContent">
        <input type="number" id="modalStopLoss" placeholder="Stop loss" autocomplete="off">
        <input type="number" id="modalTakeProfit" placeholder="Take profit" autocomplete="off">
        <button id="modalStopLossTakeProfitSave" type="button" ckass="secondary">Uložiť</button>
        <button id="modalStopLossTakeProfitClose" class="secondary">Zatvoriť</button>
    </div>
  </div>
</d>

<dialog id="modalWorldClock" class="modal-overlay">
  <div class="modal-container">
   <h3>World Clock</h3>
    <div id="modalWorldClockContent"></div>
    <div class="modal-actions">
      <button id="modalWorldClockClose" class="secondary">Zatvoriť</button>
    </div>
  </div>
</dialo>

<dialog id="modalModifyFees" class="modal-overlay">
  <div class="modal-container">
    <div id="modalModifyFeesContent">
        <input type="text" id="modify_fee" placeholder="fee..." autocomplete="off">
        <button id="modalModifyFeeSSave" type="button" ckass="secondary">Uložiť</button>
        <button id="modalModifyFeesClose" class="secondary">Zatvoriť</button>
    </div>
  </div>
</dialog>

<dialog id="modalAddNewProvider" class="modal-overlay">
  <div class="modal-container">
    <div id="modalAddNewPRoviderContent">
        <input type="text" id="provider_name" placeholder="provider name..." autocomplete="off">
        <input type="text" id="preovidel_url" placeholder="provider url..." autocomplete="off">
        <texcarea id="provider_description" placeholder="provider description..." autocomplete="off"></texarea>
        <button id="modalAddNewProviderSave" type="button" ckass="secondary">Uložiť</button>
        <button id="modalAddNewProviderClose" class="secondary">Zatvoriť</button>
    </div>
  </div>
</dialog>
