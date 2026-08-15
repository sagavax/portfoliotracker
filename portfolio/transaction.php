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
        <link rel="icon" type="image/png" sizes="32x32" href="../investment.png">
        <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
        <script src="../js/clock.js?<?php echo time() ?>" defer></script>
        <!-- <script src="../js/portfolio.js?<?php echo time() ?>" defer></script> -->
        <script src="js/transaction.js?<?php echo time() ?>" defer></script>
        <!-- <script src="js/worldclock.js?<?php echo time() ?>"></script> -->
        
    </head>

        <header>
          <a href="."><img src="../portfolio-ticker-logo.svg" alt="Portfolio Ticker"></a><div class="clockWrapper"><button type ="button" class="secondary" name="worldclock"  id="worldclock">World Clock</button><div id="clock">--:--:--</div></div>
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
                <h1>Vitajte v Portfolio Tracker Transaction Details</h1>
                <p>Tu si možete sledovat detaily transakcie.</p>

                <div class="transaction-details">
                    <table id="transaction-details-table">                       
                        <tbody id="transaction-details-body">
                            <!-- Transaction details will be populated here -->
                        </tbody>
                  

                <?php
                        
                           $transaction_id = $_GET['transaction_id'];
                           $transaction = GetTransactionDetails($transaction_id);

                        if ($transaction !== null) {
                            $id = $transaction['id'];
                            $date = $transaction['date_of_transaction'];
                            $provider = $transaction['provider'];
                            $type = $transaction['type'];
                            $asset_category = $transaction['asset_category'];
                            $symbol = $transaction['symbol'];
                            $position_type = $transaction['position_type'];
                            $spot_perpetual = $transaction['spot_perpetual'];
                            $manual_bot = $transaction['manual_bot'];
                            $quantity = $transaction['quantity'];
                            $tp_price = $transaction['tp_price'];
                            $sl_price = $transaction['sl_price'];
                            $entry_price = $transaction['entry_price'];
                            $currency = $transaction['currency'];
                            $created_at = $transaction['created_at'];
                            $modified_at = $transaction['modified_at'];
                            $is_closed = $transaction['is_closed'];

                            echo "
                            <tr>
                                <th>ID</th>
                                <td>$id</td>
                                <th>Dátum</th>
                                <td>$date</td>
                            </tr>

                            <tr>
                                <th>Poskytovateľ</th>
                                <td>$provider</td>
                                <th>Typ</th>
                                <td>$type</td>
                            </tr>

                            <tr>
                                <th>Kategória aktíva</th>
                                <td>$asset_category</td>
                                <th>Symbol</th>
                                <td>$symbol</td>
                            </tr>

                            <tr>
                                <th>Short / Long</th>
                              ";  
                                if ($position_type == 'BUY') {
                                    echo "<td class='long'>BUY</td>";
                                } else if ($position_type == 'SELL') {
                                    echo "<td class='short'>SELL</td>";
                                } 

                             echo "
                                <th>Spot/Perpetual</th>
                                <td>$spot_perpetual</td>
                            </tr>

                            <tr>
                                <th>Manuálny/Bot</th>
                                <td>$manual_bot</td>
                                <th>Množstvo</th>
                                <td>$quantity</td>
                            </tr>

                            <tr>
                                <th>TP cena</th>
                                <td>$tp_price</td>
                                <th>SL cena</th>
                                <td>$sl_price</td>
                            </tr>

                            <tr>
                                <th>Vstupná cena</th>
                                <td>$entry_price</td>
                                <th>Mena</th>
                                <td>$currency</td>
                            </tr>

                            <tr>
                                <th>Vytvorené dňa</th>
                                <td>$created_at</td>
                                <th>Zmenené dňa</th>
                                <td>$modified_at</td>
                            </tr>
                            <tr>
                                <td colspan='4'><div class='transaction-actions'><button type='button' class='secondary' name='delete_transaction' data-transaction-id='$transaction_id'><i class='fas fa-trash'></i> Zmazať
                                </button></td>
                            </tr>
                            ";
                        }
                     ?>    
                    </table>                   
            </div><!-- .transaction-details -->

            <div id="transaction-notes">
                <div id="transaction-note-header">
                    <h2>Poznámky k transakcii</h2>
                    <button type="button" class="secondary" name="add_note" id="add_note"><i class="fas fa-plus"></i> Pridať poznámku</button>
                </div>
                
                <div id="transaction-notes-container">
                    <!-- Transaction notes will be populated here -->
                     <?php
                            
                        $transaction_id = $_GET['transaction_id'];
                        $notes = GetTransactionNotes($transaction_id);

                        if (!empty($notes)) {
                            
                            foreach ($notes as $note) {
                                $note_id = $note['id'];
                                $note_text = $note['note_text'];
                                $created_at = $note['created_at'];
                                echo "<div class='transaction-note' data-note-id='$note_id'><div class='transaction-note-text'>$note_text</div>

                                
                                <div class='transaction-note-actions'>
                                    <div class='transaction-note-meta'>Vytvorené dňa: $created_at</div><button type='button' class='secondary' name='edit_note' data-note-id='$note_id'><i class='fas fa-edit'></i> upraviť</button>
                                    <button type='button' class='secondary' name='delete_note' data-note-id='$note_id'><i class='fas fa-trash'></i> remove</button>
                                </div>
                             </div>";
                            }
                            
                        } else {
                            echo "<p>No notes available for this transaction.</p>";
                        }
                     ?>
                </div>
        </div><!-- .content -->
  </body>
    <dialog id="add-note-dialog">
        <form id="add-note-form">
            <label for="note-text">Poznámka:</label>
            <textarea id="note-text" name="note_text"></textarea>
            <input type="hidden" id="transaction-id" name="transaction_id" value="<?php echo $_GET['transaction_id']; ?>">
            <div id="add-note-actions">
                <button type="submit">Uložiť</button>
                <button type="button" id="cancel-add-note">Zrušiť</button>
            </div>
        </form>    
  </html>