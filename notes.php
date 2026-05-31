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
        <link rel="stylesheet" href="css/notes.css?<?php echo time() ?>" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link href='https://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
        <link rel="icon" type="image/svg+xml" sizes="32x32" href="investment.png">
        <script type="module" src="js/notes.js?<?php echo time() ?> defer"></script>
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

                <div class="search_wrapper">
                    <input type="text" name="search_notes" id="search_notes" placeholder="Hledat poznámky...">
                </div>

              
                <div class="notes">
                    <div class="notes_header">  
                        <h3>Poznámky</h3><button type="button" name="add_note">Pridat poznamku</button>
                    </div>

                    
                    <div class="loader">Loading notes...</div>
                    
                    <?php
                    // Example: Fetch notes from database and display them
                    $get_notes_sql = "SELECT id, note_text, created_at, modified_at FROM transaction_notes  ORDER BY created_at DESC";
                    $get_notes_result = mysqli_query($link, $get_notes_sql);
                    while($row = mysqli_fetch_assoc($get_notes_result)) {
                        echo "<div class='note' data-note-id='" . $row['id'] . "'>";
                        echo "<p>" . $row['text'] . "</p>";
                        echo "<small>Created: " . $row['created_at'] . "</small>";
                        echo "<small>Modified: " . $row['modified_at'] . "</small>";
                        echo "</div>";
                    }
                    ?>
                </div>    
            </div>  
        </div><!--container-->  
  </body>
    
    <dialog id="modalAddNote">
        <div class="modal-container">
            <textarea name="noteText" id="noteText" placeholder="Zadajte text poznámy"></textarea>
            <div class="modal_note_actions">
                <button id="btnaddNote" type="button">Pridat poznamu</button>
                <button id="btnClodeModal" type="button">Zatvoriť</button>
            </div>
        </div>    
    </dialog>    
                
</html>