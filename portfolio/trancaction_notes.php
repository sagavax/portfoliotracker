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
                <h1>Transaction Notes</h1>
                <p>This is a simple page for managing transaction notes.</p>
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
  
            </div><!-- .content -->
        </div><!-- .container -->
    </body>
</html>        
 
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
  </dialog>      